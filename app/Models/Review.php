<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'barber_id',
        'barber_shop_id',
        'barber_rating',
        'shop_rating',
        'comment',
    ];

    protected $casts = [
        'barber_rating' => 'integer',
        'shop_rating'   => 'integer',
    ];

    /**
     * The booking this review is for (one review per booking).
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * The customer who wrote this review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The barber being rated.
     */
    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    /**
     * The barber shop being rated.
     */
    public function barberShop()
    {
        return $this->belongsTo(BarberShop::class);
    }
}
