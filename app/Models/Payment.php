<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'amount',
        'payment_type',
        'payment_method',
        'notes',
        'recorded_by',
        'khalti_pidx',
        'khalti_transaction_id',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // payment belongs to a booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // payment belongs to the customer who made the booking
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // shop admin who recorded a cash payment
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // only completed payments count as collected revenue
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // filter by cash payments
    public function scopeCash($query)
    {
        return $query->where('payment_method', 'cod');
    }

    // filter by online payments
    public function scopeOnline($query)
    {
        return $query->where('payment_method', 'khalti');
    }
}
