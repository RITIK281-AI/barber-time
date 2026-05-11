<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\LoyaltyTransaction;
use App\Services\LoyaltyService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class HandleExpiredBookings extends Command
{
    protected $signature = 'bookings:handle-expired';
    protected $description = 'Auto-mark no-shows and handle expired pending/confirmed bookings';

    public function __construct(protected LoyaltyService $loyaltyService)
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();

        // find all pending/confirmed bookings where appointment time has passed
        $expiredBookings = Booking::whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($now) {
                // booking date is before today
                $query->whereDate('booking_date', '<', $now->toDateString())
                    // or booking date is today but start time has passed
                    ->orWhere(function ($q) use ($now) {
                        $q->whereDate('booking_date', $now->toDateString())
                          ->whereTime('start_time', '<=', $now->toTimeString());
                    });
            })
            ->with(['user', 'service', 'items'])
            ->get();

        $noShowCount = 0;

        foreach ($expiredBookings as $booking) {
            // reverse any loyalty points redeemed for this booking
            $this->loyaltyService->reverseRedemptionIfUnpaid($booking);

            // apply no-show penalty
            $this->loyaltyService->applyNoShowPenalty($booking);

            // mark as no_show
            $booking->update(['status' => 'no_show']);

            $noShowCount++;

            $this->info("Booking #{$booking->id} marked as no-show (Date: {$booking->booking_date->format('Y-m-d')}, Time: " . Carbon::parse($booking->start_time)->format('H:i') . ")");
        }

        $this->info("Done. {$noShowCount} booking(s) marked as no-show.");

        return Command::SUCCESS;
    }
}
