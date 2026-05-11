<?php

use App\Mail\Booking\BookingReminderMail;
use App\Models\Booking;
use App\Models\LoyaltyTransaction;
use App\Services\LoyaltyService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── No-show detection ─────────────────────────────────────────────────────────
// Runs every minute.
// Catches pending AND confirmed bookings whose start_time + 10-minute grace period
// has passed and that are not already completed, cancelled, or no_show.
//
// Fine: 15% of service price, min Rs.50, max Rs.150
// Penalty: 5 loyalty points deducted (never below 0)
// Status: no_show
// cancelled_by: system
// cancellation_type: no_show

Schedule::call(function () {
    $loyaltyService = app(LoyaltyService::class);
    $now            = Carbon::now();
    $graceCutoff    = $now->copy()->subMinutes(10); // must be 10+ mins past start

    // Find all active bookings (pending OR confirmed) that are past the grace period
    // and have not already been resolved in any terminal state.
    $noShows = Booking::with(['service', 'items', 'user'])
        ->whereIn('status', ['pending', 'confirmed'])
        ->where(function ($q) use ($graceCutoff, $now) {
            // booking date is a past date entirely
            $q->whereDate('booking_date', '<', $now->toDateString())
            // or booking date is today but start_time + 10 min grace has passed
            ->orWhere(function ($q2) use ($graceCutoff) {
                $q2->whereDate('booking_date', Carbon::now()->toDateString())
                   ->whereTime('start_time', '<=', $graceCutoff->toTimeString());
            });
        })
        ->get();

    foreach ($noShows as $booking) {
        // Determine price from service or items snapshot
        $price = $booking->service?->price
               ?? $booking->items->first()?->service_price
               ?? $booking->final_amount
               ?? 0;

        if (!$price) {
            // Cannot calculate fine without a price — skip silently
            continue;
        }

        // Fine: 15% of price, min Rs.50, max Rs.150
        $rawFine = $price * 0.15;
        $fine    = round(max(50, min($rawFine, 150)), 2);

        // Reverse any redeemed loyalty points before applying penalty
        $loyaltyService->reverseRedemptionIfUnpaid($booking);

        // Mark as no_show — not cancelled, a distinct terminal status
        $booking->update([
            'status'               => 'no_show',
            'cancelled_by'         => 'system',
            'cancellation_type'    => 'no_show',
            'cancellation_fine'    => $fine,
            'fine_paid'            => false,
            'no_show_detected_at'  => now(),
        ]);

        // Deduct 5 loyalty points — penalty method floors at 0 automatically
        $loyaltyService->applyNoShowPenalty($booking);
    }

})->everyMinute()->name('process-no-show-bookings');


// ── 30-minute appointment reminders Runs every minute.
// Sends reminder email to customers whose confirmed/pending booking starts
// in the next 25–35 minutes (10-minute window handles scheduler drift).
// reminder_sent flag prevents duplicate sends.

Schedule::call(function () {
    $now         = Carbon::now();
    $windowStart = $now->copy()->addMinutes(25);
    $windowEnd   = $now->copy()->addMinutes(35);

    $bookings = Booking::with(['user', 'barberShop', 'barber', 'service', 'services'])
        ->whereIn('status', ['confirmed', 'pending'])
        ->whereRaw(
            'TIMESTAMP(booking_date, start_time) BETWEEN ? AND ?',
            [
                $windowStart->toDateTimeString(),
                $windowEnd->toDateTimeString(),
            ]
        )
        ->where('reminder_sent', false)
        ->get();

    foreach ($bookings as $booking) {
        if (!$booking->user) {
            continue;
        }

        Mail::to($booking->user->email)->send(new BookingReminderMail($booking));
        $booking->update(['reminder_sent' => true]);
    }

})->everyMinute()->name('send-booking-reminders');
