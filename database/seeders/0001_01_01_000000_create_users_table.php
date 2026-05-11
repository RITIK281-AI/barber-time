<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'barber_shop', 'barber', 'user'])->default('user');
            $table->integer('loyalty_points')->default(0);
            $table->string('phone')->nullable();
            // barber_shop_id FK is added after barber_shops table is created
            $table->unsignedBigInteger('barber_shop_id')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('address')->nullable();
            $table->boolean('notify_email')->default(true);
            $table->boolean('notify_reminders')->default(true);
            $table->boolean('notify_promotions')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
    }
};
