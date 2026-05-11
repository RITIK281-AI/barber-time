<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_closed_days', function (Blueprint $table) {
            $table->id();
            // which shop this closed day belongs to
            $table->foreignId('shop_id')->constrained('barber_shops')->onDelete('cascade');
            // 0 = Sunday, 1 = Monday ... 6 = Saturday
            $table->tinyInteger('day_of_week')->unsigned();
            $table->timestamps();

            // prevent the same day being added twice for the same shop
            $table->unique(['shop_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_closed_days');
    }
};
