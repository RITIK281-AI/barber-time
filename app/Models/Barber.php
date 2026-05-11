<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    use HasFactory;

    protected $fillable = [
        'barber_shop_id',
        'name',
        'phone',
        'email',
        'experience_years',
        'bio',
        'profile_image',
        'status',
        'unavailable_reason',
        'average_rating',
        'total_reviews',
    ];

    /**
     * A barber belongs to a barber shop
     */
    public function shop()
    {
        return $this->belongsTo(BarberShop::class, 'barber_shop_id');
    }
    /**
     * All reviews for this barber.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
