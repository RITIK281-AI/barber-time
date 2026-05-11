<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
        'role',
        'profile_photo',
        'barber_shop_id',
        'loyalty_points',
        'notify_email',
        'notify_reminders',
        'notify_promotions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'loyalty_points'    => 'integer',
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

    public function barberShop()
    {
        return $this->belongsTo(BarberShop::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function loyaltyTransactions()
    {
        return $this->hasMany(LoyaltyTransaction::class);
    }
    // user can have many favourite shops
    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }

    // check if a shop is already favourited by this user
    public function hasFavourited(int $shopId): bool
    {
        return $this->favourites()->where('barber_shop_id', $shopId)->exists();
    }


    // ── Fine Helpers ──────────────────────────────────────────────────────────

    public function hasUnpaidFine(): bool
    {
        return $this->bookings()
            ->where('cancellation_fine', '>', 0)
            ->where('fine_paid', false)
            ->exists();
    }

    // ── Loyalty Helpers ───────────────────────────────────────────────────────

    public function deductLoyaltyPoints(int $points): void
    {
        $this->loyalty_points = max(0, $this->loyalty_points - $points);
        $this->save();
    }

    public function awardLoyaltyPoints(int $points): void
    {
        $this->loyalty_points += $points;
        $this->save();
    }

    public function maxRedeemablePoints(int $payableAmount): int
    {
        $maxByAmount = (int) floor($payableAmount / 5);
        return min($this->loyalty_points, $maxByAmount);
    }

    public function canRedeem(): bool
    {
        return $this->loyalty_points >= 10;
    }
}
