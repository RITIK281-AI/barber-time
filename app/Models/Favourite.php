<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $table = 'favourite_shops';

    protected $fillable = [
        'user_id',
        'barber_shop_id',
    ];

    // belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // belongs to a barber shop
    public function barberShop()
    {
        return $this->belongsTo(BarberShop::class);
    }
}
