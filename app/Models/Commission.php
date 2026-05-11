<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $table = 'commissions';

    protected $fillable = ['key', 'value'];

    // Get a commission setting value by key with fallback.
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    // Set a commission setting value by key.
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    // Get commission rate as float, default 10.
    public static function commissionRate(): float
    {
        return (float) static::get('commission_rate', 10);
    }
}
