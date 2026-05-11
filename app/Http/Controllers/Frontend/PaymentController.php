<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Client\Response;
use App\Mail\Payment\PaymentSuccessMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct(protected LoyaltyService $loyaltyService)
    {
    }

    /**
     * Show the payment confirmation page with billing summary.
     * Replaces the old advance payment select page.
     * User sees their booking details, loyalty discount (if any),
     * final amount, and can choose Khalti or COD.
     */
    public function showConfirmPage(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($booking->payment_status !== 'unpaid') {
            return redirect()->route('frontend.bookings.index')
                ->with('info', 'This booking has already been paid.');
        }

        if ($booking->status === 'cancelled') {
            return redirect()->route('frontend.bookings.index')
                ->with('error', 'Cannot pay for a cancelled booking.');
        }

        if ($booking->status !== 'confirmed') {
            return redirect()->route('frontend.bookings.index')
            ->with('info', 'Your booking is pending shop confirmation before payment.');
        }

        $booking->load(['service', 'barber', 'barberShop']);

        return view('frontend.payments.confirm', compact('booking'));
    }

    /**
     * Initiate full payment via Khalti for a confirmed booking.
     * Sends final_amount (after loyalty discount) to Khalti, not original service price.
     */
    public function initiatePayment(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($booking->status !== 'confirmed') {
            return redirect()->route('frontend.bookings.index')
                ->with('error', 'Booking must be confirmed before payment.');
        }

        if ($booking->payment_status !== 'unpaid') {
            return redirect()->route('frontend.bookings.index')
                ->with('info', 'This booking has already been paid.');
        }

        // Uses final_amount which is the discounted amount saved at booking time.
        // Previously used $booking->service->price which ignored loyalty discounts.
        $amountToPay   = $booking->final_amount ?? $booking->service->price;
        $amountInPaisa = (int) ($amountToPay * 100);

        $baseUrl = rtrim(config('services.khalti.base_url'), '/');

        $payload = [
            'return_url'          => route('user.payment.verify', $booking->id),
            'website_url'         => config('app.url'),
            'amount'              => $amountInPaisa,
            'purchase_order_id'   => 'BOOKING-' . $booking->id . '-' . time(),
            'purchase_order_name' => 'Payment for Booking #' . $booking->id,
            'customer_info'       => [
                'name'  => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        /** @var Response $response */
        $response = Http::withHeaders([
            'Authorization' => 'key ' . config('services.khalti.secret_key'),
            'Content-Type'  => 'application/json',
        ])->post($baseUrl . '/epayment/initiate/', $payload);

        if ($response->successful()) {
            $data = $response->json();

            $booking->update(['payment_method' => 'khalti']);

            // Records the actual discounted amount charged, not the original service price.
            Payment::create([
                'booking_id'   => $booking->id,
                'user_id'      => Auth::id(),
                'amount'       => $amountToPay,
                'payment_type' => 'full',
                'khalti_pidx'  => $data['pidx'],
                'status'       => 'pending',
            ]);

            return redirect()->away($data['payment_url']);
        }

        Log::error('Khalti payment initiation failed', [
            'booking_id' => $booking->id,
            'status'     => $response->status(),
            'response'   => $response->json(),
        ]);

        return redirect()->back()
            ->with('error', 'Could not connect to Khalti. Please try again.');
    }

    /**
     * Verify full payment after Khalti redirects back to the app.
     * Marks booking as completed and awards loyalty points on final_amount.
     */
    public function verifyPayment(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $pidx = $request->query('pidx');

        if (!$pidx) {
            return redirect()->route('frontend.bookings.index')
                ->with('error', 'Invalid payment callback.');
        }

        $baseUrl = rtrim(config('services.khalti.base_url'), '/');

        /** @var Response $response */
        $response = Http::withHeaders([
            'Authorization' => 'key ' . config('services.khalti.secret_key'),
            'Content-Type'  => 'application/json',
        ])->post($baseUrl . '/epayment/lookup/', [
            'pidx' => $pidx,
        ]);

        $payment = Payment::where('booking_id', $booking->id)
            ->where('payment_type', 'full')
            ->where('khalti_pidx', $pidx)
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            return redirect()->route('frontend.bookings.index')
                ->with('error', 'Payment record not found.');
        }

        if ($response->successful() && ($response->json()['status'] ?? '') === 'Completed') {
            $data = $response->json();

            $payment->update([
                'khalti_transaction_id' => $data['transaction_id'] ?? null,
                'status'                => 'completed',
                'paid_at'               => Carbon::now(),
            ]);

            $booking->update([
                'payment_status' => 'paid',
                'status'         => 'completed',
            ]);

            // Uses LoyaltyService->awardEarnedPoints() which reads final_amount
            // and applies the correct 1pt per Rs.100 business rule.
            // Previously used a hardcoded formula: 0.1 * $booking->service->price
            // which was the wrong rate and used the pre-discount price.
            $booking->refresh();
            $this->loyaltyService->awardEarnedPoints($booking);

            $pointsEarned = (int) floor(($booking->final_amount ?? $booking->service->price) / 100);

            // Send thank-you email to customer
            $booking->load(['user', 'service', 'barberShop']);
            Mail::to($booking->user->email)->send(new PaymentSuccessMail($booking));

            return redirect()->route('frontend.bookings.index')
                ->with('success', 'Payment successful! Booking completed. You earned ' . $pointsEarned . ' loyalty points.');
        }

        $payment->update(['status' => 'failed']);

        Log::error('Khalti payment verification failed', [
            'booking_id' => $booking->id,
            'pidx'       => $pidx,
            'status'     => $response->status(),
            'response'   => $response->json(),
        ]);

        return redirect()->route('frontend.bookings.index')
            ->with('error', 'Payment verification failed. Please try again or contact support.');
    }

    /**
     * Handle Cash on Delivery selection.
     * Booking is already confirmed by admin at this point.
     * payment_status stays unpaid — shop admin marks it paid after collecting cash at shop.
     */
    public function storeCod(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (!in_array($booking->status, ['confirmed', 'completed'], true)) {
            return redirect()->route('frontend.bookings.index')
                ->with('error', 'Booking must be confirmed before selecting a payment method.');
        }

        if ($booking->payment_status !== 'unpaid') {
            return redirect()->route('frontend.bookings.index')
                ->with('info', 'This booking has already been paid.');
        }

        // Records COD as the chosen payment method.
        // payment_status intentionally stays unpaid — shop admin will mark paid after cash is collected.
        $booking->update([
            'payment_method' => 'cod',
        ]);

        Payment::create([
            'booking_id'   => $booking->id,
            'user_id'      => Auth::id(),
            'amount'       => $booking->final_amount ?? $booking->service->price,
            'payment_type' => 'full',
            'status'       => 'pending',
        ]);

        return redirect()->route('frontend.bookings.index')
            ->with('success', 'Cash on delivery selected for this booking. Please pay Rs. ' .
                number_format($booking->final_amount ?? $booking->service->price, 2) .
                ' at the shop after your service is completed. You can track this in My Bookings.');
    }

    /**
     * Initiate fine payment via Khalti.
     * Fine is 25% of service price for late cancellation or no-show.
     * User cannot make new bookings until fine is paid.
     */
    public function initiateFinePayment(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if (!$booking->hasUnpaidFine()) {
            return redirect()->back()
                ->with('error', 'No unpaid fine found for this booking.');
        }

        $amountInPaisa = (int) ($booking->cancellation_fine * 100);

        $baseUrl = rtrim(config('services.khalti.base_url'), '/');

        $payload = [
            'return_url'          => route('user.fine.verify', $booking->id),
            'website_url'         => config('app.url'),
            'amount'              => $amountInPaisa,
            'purchase_order_id'   => 'FINE-' . $booking->id . '-' . time(),
            'purchase_order_name' => 'Cancellation Fine for Booking #' . $booking->id,
            'customer_info'       => [
                'name'  => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        /** @var Response $response */
        $response = Http::withHeaders([
            'Authorization' => 'key ' . config('services.khalti.secret_key'),
            'Content-Type'  => 'application/json',
        ])->post($baseUrl . '/epayment/initiate/', $payload);

        if ($response->successful()) {
            $data = $response->json();

            Payment::create([
                'booking_id'   => $booking->id,
                'user_id'      => Auth::id(),
                'amount'       => $booking->cancellation_fine,
                'payment_type' => 'fine',
                'khalti_pidx'  => $data['pidx'],
                'status'       => 'pending',
            ]);

            return redirect()->away($data['payment_url']);
        }

        Log::error('Khalti fine payment initiation failed', [
            'booking_id' => $booking->id,
            'status'     => $response->status(),
            'response'   => $response->json(),
        ]);

        return redirect()->back()
            ->with('error', 'Could not connect to Khalti. Please try again.');
    }

    /**
     * Verify fine payment after Khalti redirects back.
     * Clears the fine block so the user can make new bookings again.
     */
    public function verifyFinePayment(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $pidx = $request->query('pidx');

        if (!$pidx) {
            return redirect()->route('frontend.bookings.index')
                ->with('error', 'Invalid payment callback.');
        }

        $baseUrl = rtrim(config('services.khalti.base_url'), '/');

        /** @var Response $response */
        $response = Http::withHeaders([
            'Authorization' => 'key ' . config('services.khalti.secret_key'),
            'Content-Type'  => 'application/json',
        ])->post($baseUrl . '/epayment/lookup/', [
            'pidx' => $pidx,
        ]);

        $payment = Payment::where('booking_id', $booking->id)
            ->where('payment_type', 'fine')
            ->where('khalti_pidx', $pidx)
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            return redirect()->route('frontend.bookings.index')
                ->with('error', 'Payment record not found.');
        }

        if ($response->successful() && ($response->json()['status'] ?? '') === 'Completed') {
            $data = $response->json();

            $payment->update([
                'khalti_transaction_id' => $data['transaction_id'] ?? null,
                'status'                => 'completed',
                'paid_at'               => Carbon::now(),
            ]);

            // Fine is cleared — user is now unblocked and can make new bookings.
            $booking->update([
                'fine_paid' => true,
            ]);

            return redirect()->route('frontend.bookings.index')
                ->with('success', 'Fine paid successfully. You can now make new bookings.');
        }

        $payment->update(['status' => 'failed']);

        Log::error('Khalti fine payment verification failed', [
            'booking_id' => $booking->id,
            'pidx'       => $pidx,
            'status'     => $response->status(),
            'response'   => $response->json(),
        ]);

        return redirect()->route('frontend.bookings.index')
            ->with('error', 'Fine payment verification failed. Please try again.');
    }
}
