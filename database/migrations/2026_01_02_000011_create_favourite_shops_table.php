<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favourite_shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('barber_shop_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // one user can only favourite a shop once
            $table->unique(['user_id', 'barber_shop_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favourite_shops');
    }
};
