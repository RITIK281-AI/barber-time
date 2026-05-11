<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_holiday_dates', function (Blueprint $table) {
            $table->id();
            // which shop this holiday belongs to
            $table->foreignId('shop_id')->constrained('barber_shops')->onDelete('cascade');
            // the specific date the shop is closed
            $table->date('date');
            // optional reason e.g. "Dashain", "Staff Training"
            $table->string('reason')->nullable();
            $table->timestamps();

            // prevent the same date being added twice for the same shop
            $table->unique(['shop_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_holiday_dates');
    }
};
