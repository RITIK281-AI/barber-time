<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShopHolidayDate extends Model
{
    use HasFactory;

    protected $fillable = ['shop_id', 'date', 'reason'];

    protected $casts = [
        // cast date string to a Carbon date instance
        'date' => 'date',
    ];

    // which shop this holiday belongs to
    public function barberShop()
    {
        return $this->belongsTo(BarberShop::class, 'shop_id');
    }
}
