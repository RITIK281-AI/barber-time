<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'barber_shop_id',
        'category_id',
        'name',
        'description',
        'price',
        'duration',
        'status',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'duration' => 'integer',
    ];

    // A service belongs to a barber shop
    public function barberShop()
    {
        return $this->belongsTo(BarberShop::class, 'barber_shop_id');
    }

    // A service belongs to a service category
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }
}
