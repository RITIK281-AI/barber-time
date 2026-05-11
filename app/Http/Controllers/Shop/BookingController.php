<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use App\Mail\Booking\BookingConfirmedCustomerMail;
use App\Mail\Booking\BookingAssignedBarberMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function __construct(protected LoyaltyService $loyaltyService) {}

    // ── Private helpers ───────────────────────────────────────────────────────

    private function shop()
    {
        $shop = Auth::user()->barberShop;

        if (!$shop) {
            abort(403, 'No barber shop associated with this account.');
        }

        return $shop;
    }

    // ── Booking list ──────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $shop = $this->shop();

        $query = Booking::with(['user', 'barber', 'service', 'payments'])
            ->where('barber_shop_id', $shop->id);

        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }
        if ($request->filled('service_id')) {
            $query->where('service_id', (int) $request->service_id);
        }
        if ($request->filled('barber_id')) {
            $query->where('barber_id', (int) $request->barber_id);
        }
        if ($request->filled('payment_status') && in_array($request->payment_status, ['paid', 'unpaid', 'partially_paid'], true)) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('status') && in_array($request->status, ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'], true)) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest('booking_date')->paginate(20)->withQueryString();

        $barbers  = Barber::where('barber_shop_id', $shop->id)->orderBy('name')->get(['id', 'name']);
        $services = Service::where('barber_shop_id', $shop->id)->orderBy('name')->get(['id', 'name']);

        return view('barbershop.bookings.index', compact('bookings', 'barbers', 'services'));
    }

    public function show($id)
    {
        $shop    = $this->shop();
        $booking = Booking::with(['user', 'barber', 'service', 'items.service', 'payments', 'review'])
            ->where('barber_shop_id', $shop->id)
            ->findOrFail($id);

        return view('barbershop.bookings.show', compact('booking'));
    }

    // ── Update booking status ─────────────────────────────────────────────────
    //
    // Shop admin can:
    //   pending   → confirmed
    //   confirmed → completed
    //   pending|confirmed → cancelled  (no fine/points ever on shop-side cancel)

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        $shop    = $this->shop();
        $booking = Booking::with(['service', 'user'])
            ->where('barber_shop_id', $shop->id)
            ->findOrFail($id);

        // ── Confirm ───────────────────────────────────────────────────────────
       if ($booking->status === 'pending' && $validated['status'] === 'confirmed') {
            $booking->update(['status' => 'confirmed']);

            $freshBooking = $booking->fresh(['user', 'barber', 'service', 'items', 'barberShop']);

            // Email to customer
            if ($freshBooking->user?->email) {
                Mail::to($freshBooking->user->email)
                    ->send(new BookingConfirmedCustomerMail($freshBooking));
            }

            // Email to barber
            if ($freshBooking->barber?->email) {
                Mail::to($freshBooking->barber->email)
                    ->send(new BookingAssignedBarberMail($freshBooking));
            }

            return redirect()
                ->route('shop.bookings.index')
                ->with('success', 'Booking confirmed successfully.');
        }
        // ── Complete ──────────────────────────────────────────────────────────
        if ($booking->status === 'confirmed' && $validated['status'] === 'completed') {
            $updates = ['status' => 'completed'];

            if ($booking->payment_status !== 'paid') {
                $updates['payment_status'] = 'paid';
            }

            $booking->update($updates);

            // Award loyalty points — skip if Khalti already awarded on payment verification
            if ($booking->payment_method !== 'khalti') {
                $this->loyaltyService->awardEarnedPoints($booking->fresh());
            }

            return redirect()
                ->route('shop.bookings.index')
                ->with('success', 'Booking marked as completed.');
        }

        // ── Shop cancel ───────────────────────────────────────────────────────
        // When the shop admin cancels:
        //   - NO cash fine on the customer
        //   - NO loyalty point deduction
        //   - Redeemed points ARE returned (it's not the customer's fault)
        //   - cancelled_by = 'shop' so it's visible in the UI/reports
        if (
            in_array($booking->status, ['pending', 'confirmed'])
            && $validated['status'] === 'cancelled'
        ) {
            // Return any redeemed loyalty points — shop cancelled, customer is not at fault
            $this->loyaltyService->reverseRedemptionIfUnpaid($booking);

            $booking->update([
                'status'            => 'cancelled',
                'cancelled_by'      => 'shop',
                'cancellation_type' => null,   // no cancellation type applies to shop cancel
                'cancellation_fine' => 0,
                'fine_paid'         => false,
            ]);

            return redirect()
                ->route('shop.bookings.index')
                ->with('success', 'Booking cancelled. No penalty applied to the customer.');
        }

        // If none of the above matched, the transition is invalid
        return redirect()
            ->route('shop.bookings.index')
            ->with('error', 'Invalid status transition.');
    }

    // ── Mark COD payment received and booking complete ────────────────────────

    public function markPaid($id)
    {
        $shop    = $this->shop();
        $booking = Booking::with(['service', 'user'])
            ->where('barber_shop_id', $shop->id)
            ->findOrFail($id);

        if ($booking->status !== 'confirmed') {
            return redirect()->route('shop.bookings.index')
                ->with('error', 'Only confirmed bookings can be marked as paid.');
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route('shop.bookings.index')
                ->with('info', 'This booking is already fully paid.');
        }

        $booking->update([
            'payment_status' => 'paid',
            'status'         => 'completed',
        ]);

        $this->loyaltyService->awardEarnedPoints($booking->fresh());

        $pointsEarned = (int) floor(($booking->final_amount ?: $booking->original_amount) / 100);

        return redirect()->route('shop.bookings.index')
            ->with('success', 'Payment received and booking completed.' .
                ($pointsEarned > 0 ? " {$pointsEarned} loyalty points awarded to customer." : ''));
    }

    // ── Mark COD booking as complete (collect cash at visit) ─────────────────

    public function markComplete(int $id)
    {
        $shop    = $this->shop();
        $booking = Booking::with('user')
            ->where('barber_shop_id', $shop->id)
            ->findOrFail($id);

        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed bookings can be marked as completed.');
        }

        if ($booking->payment_method !== 'cod') {
            return back()->with('info', 'Online bookings are completed automatically after successful payment.');
        }

        DB::transaction(function () use ($booking) {
            $payment = Payment::where('booking_id', $booking->id)
                ->where('payment_type', 'full')
                ->where('status', 'pending')
                ->latest('id')
                ->first();

            if ($payment) {
                $payment->update([
                    'payment_method' => 'cod',
                    'status'         => 'completed',
                    'recorded_by'    => Auth::id(),
                    'paid_at'        => now(),
                ]);
            } else {
                Payment::create([
                    'booking_id'     => $booking->id,
                    'user_id'        => $booking->user_id,
                    'amount'         => $booking->final_amount,
                    'payment_type'   => 'full',
                    'payment_method' => 'cod',
                    'status'         => 'completed',
                    'recorded_by'    => Auth::id(),
                    'paid_at'        => now(),
                ]);
            }

            $booking->update([
                'status'         => 'completed',
                'payment_status' => 'paid',
            ]);
        });

        $this->loyaltyService->awardEarnedPoints($booking->fresh());

        return back()->with('success', 'Booking marked as completed and COD payment recorded.');
    }

    // ── Record cash received for a COD booking ────────────────────────────────

    public function recordCash(Request $request, int $id)
    {
        $shop    = $this->shop();
        $booking = Booking::where('barber_shop_id', $shop->id)->findOrFail($id);

        if (
            $booking->payment_method !== 'cod'
            || $booking->payment_status !== 'unpaid'
            || !in_array($booking->status, ['confirmed', 'completed'], true)
        ) {
            return back()->with('error', 'This booking is not eligible for cash recording.');
        }

        $request->validate(['notes' => 'nullable|string|max:255']);

        DB::transaction(function () use ($booking, $request) {
            $payment = Payment::where('booking_id', $booking->id)
                ->where('payment_type', 'full')
                ->where('status', 'pending')
                ->latest('id')
                ->first();

            if ($payment) {
                $payment->update([
                    'payment_method' => 'cod',
                    'status'         => 'completed',
                    'notes'          => $request->input('notes'),
                    'recorded_by'    => Auth::id(),
                    'paid_at'        => now(),
                ]);
            } else {
                Payment::create([
                    'booking_id'     => $booking->id,
                    'user_id'        => $booking->user_id,
                    'amount'         => $booking->final_amount,
                    'payment_type'   => 'full',
                    'payment_method' => 'cod',
                    'status'         => 'completed',
                    'notes'          => $request->input('notes'),
                    'recorded_by'    => Auth::id(),
                    'paid_at'        => now(),
                ]);
            }

            $booking->update([
                'payment_status' => 'paid',
                'status'         => 'completed',
            ]);
        });

        $this->loyaltyService->awardEarnedPoints($booking->fresh());

        return back()->with('success', 'Cash payment recorded successfully.');
    }
}
