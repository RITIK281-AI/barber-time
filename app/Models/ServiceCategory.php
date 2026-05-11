<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'sort_order'];

    /**
     * A category has many services across all shops.
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }
}
