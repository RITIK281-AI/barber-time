<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'barber_shop_id',
        'barber_id',
        'service_id',
        'booking_date',
        'start_time',
        'end_time',
        'total_price',
        'total_duration_minutes',
        'final_price',
        'status',
        'payment_status',
        'payment_method',
        'cancellation_fine',
        'fine_paid',
        'khalti_pidx',
        'advance_amount',
        'remaining_amount',
        'original_amount',
        'discount_amount',
        'final_amount',
        'redeemed_points',
        'fine_amount',
        'commission_amount',
        'shop_earnings',
        'commission_rate',
        'cancelled_by',
        'cancellation_type',
        'no_show_detected_at',
    ];

    protected $casts = [
        'booking_date'           => 'date',
        'start_time'             => 'datetime:H:i',
        'end_time'               => 'datetime:H:i',
        'total_price'            => 'decimal:2',
        'total_duration_minutes' => 'integer',
        'final_price'            => 'decimal:2',
        'fine_paid'              => 'boolean',
        'original_amount'        => 'integer',
        'discount_amount'        => 'integer',
        'final_amount'           => 'integer',
        'redeemed_points'        => 'integer',
        'fine_amount'            => 'integer',
        'commission_amount'      => 'decimal:2',
        'shop_earnings'          => 'decimal:2',
        'commission_rate'        => 'decimal:2',
        'no_show_detected_at'    => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user()        { return $this->belongsTo(User::class); }
    public function barberShop()  { return $this->belongsTo(BarberShop::class); }
    public function barber()      { return $this->belongsTo(Barber::class); }
    public function service()     { return $this->belongsTo(Service::class); }
    public function items()       { return $this->hasMany(BookingItem::class); }
    public function review()      { return $this->hasOne(Review::class); }
    public function payments()    { return $this->hasMany(Payment::class); }
    public function loyaltyTransactions() { return $this->hasMany(LoyaltyTransaction::class); }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_items', 'booking_id', 'service_id');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeUpcoming($query)
    {
        return $query->whereDate('booking_date', '>=', now());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('booking_date', today());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeRevenue($query)
    {
        return $query->where('status', 'completed')->where('payment_status', 'paid');
    }

    public function scopePendingCash($query)
    {
        return $query->where('payment_method', 'cod')
                     ->where('payment_status', 'unpaid')
                     ->where('status', 'completed');
    }

    public function scopeCod($query)    { return $query->where('payment_method', 'cod'); }
    public function scopeOnline($query) { return $query->where('payment_method', 'khalti'); }

    // ── Status helpers ────────────────────────────────────────────────────────

    // Returns the Carbon datetime of the appointment start.
    public function appointmentStartsAt(): Carbon
    {
        return Carbon::parse(
            $this->booking_date->format('Y-m-d') . ' ' .
            Carbon::parse($this->start_time)->format('H:i:s')
        );
    }

    // True when now is past the appointment start time.
    public function isPastStartTime(): bool
    {
        return Carbon::now()->gt($this->appointmentStartsAt());
    }

    // True when now is past start_time + grace period (10 minutes).
    // Used by the scheduler to give a small buffer before marking no-show.
    public function isPastGracePeriod(int $graceMinutes = 10): bool
    {
        return Carbon::now()->gt($this->appointmentStartsAt()->addMinutes($graceMinutes));
    }

    // True if the customer is cancelling within the late-cancel window (between T-1hr and T+0 / start time).
    // Once start time has passed, the customer can no longer cancel — it becomes no-show.
    public function isLateCancellation(): bool
    {
        if (!in_array($this->status, ['pending', 'confirmed'], true)) {
            return false;
        }

        $start = $this->appointmentStartsAt();
        $now   = Carbon::now();

        // within 1 hour before start — not yet past start
        return $now->gte($start->copy()->subHour()) && $now->lt($start);
    }

    // True if start time has already passed — customer can no longer cancel.
    public function isCancelBlockedByTime(): bool
    {
        return $this->isPastStartTime();
    }

    // True if this booking qualifies for auto no-show detection by the scheduler.
    // Catches pending AND confirmed bookings past the grace period.
    public function isEligibleForNoShow(int $graceMinutes = 10): bool
    {
        return in_array($this->status, ['pending', 'confirmed'])
            && $this->isPastGracePeriod($graceMinutes);
    }

    // ── Fine helpers ──────────────────────────────────────────────────────────

    public function hasUnpaidFine(): bool
    {
        return $this->cancellation_fine > 0 && !$this->fine_paid;
    }

    // ── Loyalty helpers ───────────────────────────────────────────────────────

    public function hasRedemption(): bool
    {
        return $this->redeemed_points > 0;
    }

    public function isPaymentCaptured(): bool
    {
        return in_array($this->status, ['completed', 'paid']);
    }

    public function isCodPending(): bool
    {
        return $this->payment_method === 'cod'
            && $this->payment_status === 'unpaid'
            && $this->status === 'completed';
    }

    // ── Commission helpers ────────────────────────────────────────────────────

    public function calculateCommission(float $rate): array
    {
        $commission  = round($this->final_amount * ($rate / 100), 2);
        $shopEarning = round($this->final_amount - $commission, 2);

        return [
            'commission_rate'   => $rate,
            'commission_amount' => $commission,
            'shop_earnings'     => $shopEarning,
        ];
    }

    public function hasCommission(): bool
    {
        return $this->commission_amount > 0;
    }
}
