<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'service_id',
        'service_name',
        'service_price',
        'service_duration',
        'category_name',
    ];

    protected $casts = [
        'service_price'    => 'decimal:2',
        'service_duration' => 'integer',
    ];

    /**
     * The booking this item belongs to.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * The original service (nullable — service may be deleted later).
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
