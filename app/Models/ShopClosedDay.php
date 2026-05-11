<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShopClosedDay extends Model
{
    use HasFactory;

    protected $fillable = ['shop_id', 'day_of_week'];

    // day number to name mapping used across the app
    public const DAY_NAMES = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    // which shop this closed day belongs to
    public function barberShop()
    {
        return $this->belongsTo(BarberShop::class, 'shop_id');
    }

    // returns the day name string e.g. "Monday"
    public function getDayNameAttribute(): string
    {
        return self::DAY_NAMES[$this->day_of_week] ?? 'Unknown';
    }
}
