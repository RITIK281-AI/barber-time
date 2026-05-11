<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->enum('type', [
                'earn',
                'redeem',
                'late_cancel_penalty',
                'no_show_penalty',
                'redemption_reversal',
                'admin_adjustment',
            ]);
            $table->integer('points');
            $table->unsignedInteger('amount_rs')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
    }
};
