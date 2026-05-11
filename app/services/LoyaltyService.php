<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\LoyaltyTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoyaltyService
{
    // ── Constants ─────────────────────────────────────────────────────────────
    public const MIN_REDEEM_POINTS     = 10;
    public const RS_PER_POINT          = 5;
    public const POINTS_PER_100_RS     = 1;
    public const LATE_CANCEL_PENALTY   = 3;   // points deducted on late cancel
    public const NO_SHOW_PENALTY       = 5;   // points deducted on no-show
    public const FREE_SERVICE_VALUE_RS = 300;
    public const FREE_SERVICE_POINTS   = 50;

    private const VALID_TIERS = [10, 20, 30, 50];

    public function redeemableTiersFor(User $user): array
    {
        return array_values(array_filter(
            self::VALID_TIERS,
            fn (int $tier): bool => $tier <= $user->loyalty_points
        ));
    }

    // ── Discount calculation ──────────────────────────────────────────────────

    public function calculateDiscount(int $points, int $originalAmount): int
    {
        if ($points === self::FREE_SERVICE_POINTS) {
            return min(self::FREE_SERVICE_VALUE_RS, $originalAmount);
        }

        $discount = $points * self::RS_PER_POINT;
        return min($discount, $originalAmount);
    }

    public function maxRedeemablePoints(User $user): int
    {
        $tiers = $this->redeemableTiersFor($user);
        return empty($tiers) ? 0 : max($tiers);
    }

    // ── Earning ───────────────────────────────────────────────────────────────
    // Called after payment is confirmed (Khalti verify OR shop marks COD paid).

    public function awardEarnedPoints(Booking $booking): void
    {
        $user       = $booking->user;
        $paidAmount = $booking->final_amount ?: $booking->original_amount;

        if ($paidAmount <= 0) {
            return;
        }

        $pointsEarned = (int) floor($paidAmount / 100) * self::POINTS_PER_100_RS;

        if ($pointsEarned <= 0) {
            return;
        }

        DB::transaction(function () use ($user, $booking, $pointsEarned, $paidAmount) {
            $user = User::lockForUpdate()->findOrFail($user->id);

            $alreadyAwarded = LoyaltyTransaction::where('booking_id', $booking->id)
                ->where('type', 'earn')
                ->exists();

            if ($alreadyAwarded) {
                return;
            }

            $user->increment('loyalty_points', $pointsEarned);

            LoyaltyTransaction::create([
                'user_id'     => $user->id,
                'booking_id'  => $booking->id,
                'type'        => 'earn',
                'points'      => $pointsEarned,
                'amount_rs'   => $paidAmount,
                'description' => "Earned {$pointsEarned} pts from Rs.{$paidAmount} on booking #{$booking->id}",
            ]);
        });
    }

    // ── Penalties ─────────────────────────────────────────────────────────────

    public function applyLateCancelPenalty(Booking $booking): void
    {
        $this->applyPenalty(
            booking:     $booking,
            type:        'late_cancel_penalty',
            penalty:     self::LATE_CANCEL_PENALTY,
            description: "Late cancellation penalty for booking #{$booking->id}"
        );
    }

    public function applyNoShowPenalty(Booking $booking): void
    {
        $this->applyPenalty(
            booking:     $booking,
            type:        'no_show_penalty',
            penalty:     self::NO_SHOW_PENALTY,
            description: "No-show penalty for booking #{$booking->id}"
        );
    }

    // Shared penalty logic — always floors at 0, never goes negative.
    private function applyPenalty(
        Booking $booking,
        string  $type,
        int     $penalty,
        string  $description
    ): void {
        DB::transaction(function () use ($booking, $type, $penalty, $description) {
            $user            = User::lockForUpdate()->findOrFail($booking->user_id);
            $actualDeduction = min($penalty, $user->loyalty_points);

            if ($actualDeduction > 0) {
                $user->decrement('loyalty_points', $actualDeduction);

                LoyaltyTransaction::create([
                    'user_id'     => $user->id,
                    'booking_id'  => $booking->id,
                    'type'        => $type,
                    'points'      => -$actualDeduction,
                    'amount_rs'   => 0,
                    'description' => $description,
                ]);
            }
        });
    }

    // ── Reversal ──────────────────────────────────────────────────────────────
    // Returns redeemed points to the customer if booking is cancelled before payment.
    // Safe to call even if no points were redeemed (it checks internally).

    public function reverseRedemptionIfUnpaid(Booking $booking): void
    {
        if (!$booking->hasRedemption()) {
            return;
        }

        if ($booking->isPaymentCaptured()) {
            // Payment already went through — do not reverse.
            return;
        }

        $pointsToReturn = $booking->redeemed_points;

        DB::transaction(function () use ($booking, $pointsToReturn) {
            $user = User::lockForUpdate()->findOrFail($booking->user_id);
            $user->increment('loyalty_points', $pointsToReturn);

            LoyaltyTransaction::create([
                'user_id'     => $user->id,
                'booking_id'  => $booking->id,
                'type'        => 'redemption_reversal',
                'points'      => $pointsToReturn,
                'amount_rs'   => $booking->discount_amount,
                'description' => "Reversed {$pointsToReturn} redeemed pts — booking #{$booking->id} cancelled before payment",
            ]);

            $booking->update([
                'discount_amount' => 0,
                'final_amount'    => $booking->original_amount,
                'redeemed_points' => 0,
            ]);
        });
    }
}
