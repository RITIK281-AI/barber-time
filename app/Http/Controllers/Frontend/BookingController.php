<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BarberShop;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\LoyaltyTransaction;
use App\Models\Service;
use App\Models\ShopClosedDay;
use App\Models\ShopHolidayDate;
use App\Models\User;
use App\Services\LoyaltyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Mail\Booking\BookingNotificationMail;
use App\Mail\Booking\BookingCancelledMail;
use App\Models\Commission;

class BookingController extends Controller
{
    public function __construct(protected LoyaltyService $loyaltyService) {}

    // ── Private helpers ───────────────────────────────────────────────────────

    private function hasConflict(int $barberId, string $date, string $start, string $end): bool
    {
        return Booking::where('barber_id', $barberId)
            ->where('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->where(fn($q) => $q->where('start_time', '<', $end)->where('end_time', '>', $start))
            ->exists();
    }

    private function openingHoursError(BarberShop $shop, Carbon $start, Carbon $end, string $date): ?string
    {
        $opening  = Carbon::parse("{$date} {$shop->opening_time}");
        $closing  = Carbon::parse("{$date} {$shop->closing_time}");
        $openFmt  = Carbon::parse($shop->opening_time)->format('g:i A');
        $closeFmt = Carbon::parse($shop->closing_time)->format('g:i A');

        if ($start->lt($opening)) {
            return "The shop opens at {$openFmt}. Please choose a later time.";
        }
        if ($end->gt($closing)) {
            return "Your appointment would end after closing time ({$closeFmt}). Please choose an earlier time.";
        }
        return null;
    }

    // ── Booked slots API (used by JS on booking form) ─────────────────────────

    public function bookedSlots(Request $request, $shop)
    {
        $shopModel = BarberShop::findOrFail($shop);
        $dayOfWeek = Carbon::parse($request->date)->dayOfWeek;

        $isClosed = ShopClosedDay::where('shop_id', $shopModel->id)
            ->where('day_of_week', $dayOfWeek)
            ->exists();

        if ($isClosed) {
            $dayName = ShopClosedDay::DAY_NAMES[$dayOfWeek];
            return response()->json(['closed' => true, 'message' => "Shop is closed on {$dayName}s."], 200);
        }

        $isHoliday = ShopHolidayDate::where('shop_id', $shopModel->id)
            ->where('date', $request->date)
            ->exists();

        if ($isHoliday) {
            return response()->json(['closed' => true, 'message' => 'Shop is closed on this date.'], 200);
        }

        $request->validate([
            'barber_id' => 'required|exists:barbers,id',
            'date'      => 'required|date',
        ]);

        $slots = Booking::where('barber_id', $request->barber_id)
            ->whereDate('booking_date', $request->date)
            ->whereIn('status', ['pending', 'confirmed', 'completed'])
            ->get()
            ->map(fn($b) => [
                'start' => Carbon::parse($b->start_time)->format('H:i'),
                'end'   => Carbon::parse($b->end_time)->format('H:i'),
            ]);

        return response()->json($slots->values());
    }

    // ── Booking form ──────────────────────────────────────────────────────────

    public function create(Request $request, $shop)
    {
        /** @var User $user */
        $user = Auth::user();

        $unpaidFines = $user->bookings()
            ->where('cancellation_fine', '>', 0)
            ->where('fine_paid', false)
            ->with(['service', 'barberShop'])
            ->get();

        $shop = BarberShop::where('status', 'approved')
            ->with([
                'barbers'  => fn($q) => $q->where('status', 'active')->orderBy('name'),
                'services' => fn($q) => $q->where('status', 'active')->orderBy('price'),
            ])
            ->findOrFail($shop);

        $serviceIds       = array_filter(explode(',', $request->query('service_ids', '')));
        $selectedServices = $shop->services->whereIn('id', $serviceIds)->values();
        $maxRedeemable    = $this->loyaltyService->maxRedeemablePoints($user);
        $canRedeem        = $user->loyalty_points >= LoyaltyService::MIN_REDEEM_POINTS;

        return view('frontend.bookings.create', compact(
            'shop', 'selectedServices', 'user', 'maxRedeemable', 'canRedeem', 'unpaidFines'
        ));
    }

    // ── Store booking ─────────────────────────────────────────────────────────

    public function store(Request $request, $shop)
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasUnpaidFine()) {
            return redirect()->back()->with('error',
                'You have an unpaid fine. Please pay your fine before making a new booking.'
            );
        }

        $validated = $request->validate([
            'service_ids'  => 'required|string',
            'barber_id'    => 'required|exists:barbers,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time'   => 'required|date_format:H:i',
        ]);

        $shopModel     = BarberShop::findOrFail($shop);
        $serviceIds    = array_filter(explode(',', $validated['service_ids']));
        $services      = Service::with('category')->whereIn('id', $serviceIds)->get();
        $totalPrice    = $services->sum('price');
        $totalDuration = $services->sum('duration');

        $start = Carbon::parse("{$validated['booking_date']} {$validated['start_time']}");
        $end   = $start->copy()->addMinutes($totalDuration);

        if ($start->isPast()) {
            return back()->withErrors(['start_time' => 'You cannot book a time slot that has already passed.'])->withInput();
        }

        if ($error = $this->openingHoursError($shopModel, $start, $end, $validated['booking_date'])) {
            return back()->withErrors(['start_time' => $error])->withInput();
        }

        $dayOfWeek = Carbon::parse($validated['booking_date'])->dayOfWeek;
        $isClosed  = ShopClosedDay::where('shop_id', $shopModel->id)->where('day_of_week', $dayOfWeek)->exists();

        if ($isClosed) {
            $dayName = ShopClosedDay::DAY_NAMES[$dayOfWeek];
            return back()->withErrors(['booking_date' => "This shop is closed on {$dayName}s. Please choose a different date."])->withInput();
        }

        $isHoliday = ShopHolidayDate::where('shop_id', $shopModel->id)->where('date', $validated['booking_date'])->exists();

        if ($isHoliday) {
            return back()->withErrors(['booking_date' => 'This shop is closed on the selected date. Please choose a different date.'])->withInput();
        }

        $startStr = $start->format('H:i:s');
        $endStr   = $end->format('H:i:s');

        if ($this->hasConflict($validated['barber_id'], $validated['booking_date'], $startStr, $endStr)) {
            return back()->withErrors(['start_time' => 'This time slot is already booked. Please choose another.'])->withInput();
        }

        $pointsToRedeem = (int) $request->input('points_to_redeem', 0);
        $discountAmount = 0;
        $finalAmount    = $totalPrice;

        if ($pointsToRedeem > 0) {
            $request->validate([
                'points_to_redeem' => [
                    'integer',
                    Rule::in($this->loyaltyService->redeemableTiersFor($user)),
                ],
            ]);

            $discountAmount = $this->loyaltyService->calculateDiscount($pointsToRedeem, $totalPrice);
            $finalAmount    = max(0, $totalPrice - $discountAmount);
        }

        try {
            $booking = DB::transaction(function () use (
                $user, $shop, $validated, $services, $startStr, $endStr,
                $totalPrice, $discountAmount, $finalAmount, $pointsToRedeem
            ) {
                $commissionRate = Commission::commissionRate();
                $commissionAmt  = round($finalAmount * ($commissionRate / 100), 2);
                $shopEarnings   = round($finalAmount - $commissionAmt, 2);

                $booking = Booking::create([
                    'user_id'           => $user->id,
                    'barber_shop_id'    => (int) $shop,
                    'service_id'        => $services->first()->id,
                    'barber_id'         => $validated['barber_id'],
                    'booking_date'      => $validated['booking_date'],
                    'start_time'        => $startStr,
                    'end_time'          => $endStr,
                    'commission_rate'   => $commissionRate,
                    'commission_amount' => $commissionAmt,
                    'shop_earnings'     => $shopEarnings,
                    'status'            => 'pending',
                    'original_amount'   => $totalPrice,
                    'discount_amount'   => $discountAmount,
                    'final_amount'      => $finalAmount,
                    'redeemed_points'   => $pointsToRedeem,
                ]);

                foreach ($services as $svc) {
                    BookingItem::create([
                        'booking_id'       => $booking->id,
                        'service_id'       => $svc->id,
                        'service_name'     => $svc->name,
                        'service_price'    => $svc->price,
                        'service_duration' => $svc->duration,
                        'category_name'    => $svc->category?->name,
                    ]);
                }

                if ($pointsToRedeem > 0) {
                    $lockedUser = \App\Models\User::lockForUpdate()->findOrFail($user->id);

                    if ($lockedUser->loyalty_points < $pointsToRedeem) {
                        throw new \RuntimeException('Insufficient loyalty points.');
                    }

                    $lockedUser->decrement('loyalty_points', $pointsToRedeem);

                    LoyaltyTransaction::create([
                        'user_id'     => $lockedUser->id,
                        'booking_id'  => $booking->id,
                        'type'        => 'redeem',
                        'points'      => -$pointsToRedeem,
                        'amount_rs'   => $discountAmount,
                        'description' => "Redeemed {$pointsToRedeem} pts for Rs.{$discountAmount} discount on booking #{$booking->id}",
                    ]);
                }

                return $booking;
            });

            $successMsg = 'Booking request submitted!';
            if ($pointsToRedeem > 0) {
                $successMsg .= " Rs.{$discountAmount} loyalty discount applied.";
            }

            $barberShopAdmin = $booking->barberShop->user;
            if ($barberShopAdmin) {
                Mail::to($barberShopAdmin->email)->send(new BookingNotificationMail($booking));
            }

            return redirect()->route('frontend.bookings.index')->with('success', $successMsg);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['booking_error' => 'Failed to create booking: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // ── Booking list ──────────────────────────────────────────────────────────

    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['barberShop', 'barber', 'service', 'review'])
            ->latest()
            ->get();

        return view('frontend.bookings.index', compact('bookings'));
    }

    // ── Cancel booking ────────────────────────────────────────────────────────
    //
    // Rules:
    //  > 1 hour before start  → early cancel, no fine, no points
    //  within 1 hr before start → late cancel, fine + 3 pts
    //  AT or AFTER start time → blocked, must be handled as no-show by scheduler

    public function cancel($id)
    {
        /** @var User $user */
        $user    = Auth::user();
        $booking = Booking::where('user_id', Auth::id())
            ->with('service', 'items', 'barberShop.user')
            ->findOrFail($id);

        // ── Guard: only cancellable statuses ──────────────────────────────────
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        // ── Guard: paid bookings cannot be cancelled ───────────────────────────
        if ($booking->payment_status === 'paid') {
            return back()->with('error', 'Paid bookings cannot be cancelled.');
        }

        // ── Guard: past start time → no cancellation allowed ──────────────────
        // Once the appointment time has started, the customer can no longer
        // self-cancel. The scheduler will handle this as a no-show.
        if ($booking->isCancelBlockedByTime()) {
            return back()->with('error',
                'Your appointment time has already passed. This booking will be automatically reviewed.'
            );
        }

        // ── Step 1: always reverse any redeemed points first ──────────────────
        $this->loyaltyService->reverseRedemptionIfUnpaid($booking);

        // ── Step 2: determine cancellation type ───────────────────────────────
        if ($booking->isLateCancellation()) {

            // Late cancel: within 1 hour of start time
            $price = $booking->service?->price
                  ?? $booking->items->first()?->service_price
                  ?? $booking->final_amount
                  ?? 0;

            // Fine: 10% of service price, min Rs.30, max Rs.80
            $rawFine = $price * 0.10;
            $fine    = round(max(30, min($rawFine, 80)), 2);

            $booking->update([
                'status'            => 'cancelled',
                'cancelled_by'      => 'customer',
                'cancellation_type' => 'late',
                'cancellation_fine' => $fine,
                'fine_paid'         => false,
            ]);

            // Deduct 3 loyalty points — never below zero
            $this->loyaltyService->applyLateCancelPenalty($booking);

            $barberShopAdmin = $booking->barberShop->user;
            if ($barberShopAdmin) {
                Mail::to($barberShopAdmin->email)->send(new BookingCancelledMail($booking));
            }

            return back()->with('warning',
                "Booking cancelled. A fine of Rs.{$fine} has been applied for late cancellation."
            );
        }

        // Early cancel: more than 1 hour before start — no fine, no points lost
        $booking->update([
            'status'            => 'cancelled',
            'cancelled_by'      => 'customer',
            'cancellation_type' => 'early',
        ]);

        $barberShopAdmin = $booking->barberShop->user;
        if ($barberShopAdmin) {
            Mail::to($barberShopAdmin->email)->send(new BookingCancelledMail($booking));
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }

    // ── Show single booking ───────────────────────────────────────────────────

    public function show($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->with(['barberShop', 'service', 'services', 'barber', 'items.service', 'payments'])
            ->findOrFail($id);

        return view('frontend.bookings.show', compact('booking'));
    }

    public function success()
    {
        return view('frontend.bookings.success');
    }
}
