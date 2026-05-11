<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('barber_shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('barber_id')->constrained()->onDelete('cascade');
            // nullable because multi-service bookings use booking_items instead
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('cascade');

            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');

            // total_price, total_duration, final_price for multi-service support
            $table->decimal('total_price', 10, 2)->default(0);
            $table->unsignedInteger('total_duration_minutes')->default(0);
            $table->decimal('final_price', 10, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);

            // status includes no_show
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled', 'no_show'])->default('pending');

            // payment_status includes partially_paid for advance payments
            $table->enum('payment_status', ['unpaid', 'partially_paid', 'paid'])->default('unpaid');
            $table->enum('payment_method', ['khalti', 'cod'])->nullable();

            // khalti payment tracking
            $table->string('khalti_pidx')->nullable();
            $table->decimal('advance_amount', 8, 2)->default(0.00);
            $table->decimal('remaining_amount', 8, 2)->default(0.00);

            // pricing breakdown
            $table->unsignedInteger('original_amount')->default(0);
            $table->unsignedInteger('discount_amount')->default(0);
            $table->unsignedInteger('final_amount')->default(0);
            $table->unsignedInteger('redeemed_points')->default(0);
            $table->unsignedInteger('fine_amount')->default(0);

            // cancellation fine
            $table->decimal('cancellation_fine', 8, 2)->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->boolean('reminder_sent')->default(false);

            $table->timestamps();

            // indexes for revenue queries
            $table->index(['status', 'payment_status'], 'bookings_status_payment_idx');
            $table->index(['barber_shop_id', 'status', 'payment_status'], 'bookings_shop_revenue_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
