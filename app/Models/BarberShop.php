<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarberShop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'shop_image',
        'shop_license',
        'registration_document',
        'tax_clearance_document',
        'latitude',
        'longitude',
        'phone',
        'opening_time',
        'closing_time',
        'owner_name',
        'status',
        'average_rating',
        'total_reviews',
        'email',
        'district',
        'city',
        'pan_number',
        'business_license_number',
        'business_registration_date',
        'shop_area_sqft',
        'number_of_barbers',
        'number_of_chairs',
        'years_of_experience',
        'emergency_contact_name',
        'emergency_contact_phone',
        'services_offered',
        'description',
        'admin_remarks',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at'    => 'datetime',
        'average_rating' => 'decimal:1',
        'total_reviews'  => 'integer',
        'latitude'       => 'decimal:7',
        'longitude'      => 'decimal:7',
    ];

    // a barber shop has many users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // a barber shop has one main admin user
    public function user()
    {
        return $this->hasOne(User::class);
    }

    // a barber shop has many barbers
    public function barbers()
    {
        return $this->hasMany(Barber::class);
    }

    // a barber shop has many services
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // a barber shop has many bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // a barber shop has many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // weekly closed weekdays configured by the shop
    public function closedDays()
    {
        return $this->hasMany(ShopClosedDay::class, 'shop_id');
    }

    // specific closed holiday dates configured by the shop
    public function holidayDates()
    {
        return $this->hasMany(ShopHolidayDate::class, 'shop_id');
    }

    // shops can be favourited by many users
    public function favouritedBy()
    {
        return $this->hasMany(Favourite::class);
    }

    // category relationship
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }
}
