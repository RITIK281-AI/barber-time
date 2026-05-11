<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                  ->constrained('bookings')
                  ->onDelete('cascade');

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->decimal('amount', 10, 2);

            // payment_type and payment_for kept as strings for flexibility
            $table->string('payment_type')->nullable();
            $table->string('payment_for')->nullable();

            // track whether payment was cash or online
            $table->enum('payment_method', ['cod', 'khalti'])->nullable();

            // shop admin note when recording cash payment
            $table->string('notes')->nullable();

            // who recorded the cash payment (shop admin user id)
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->foreign('recorded_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->string('khalti_pidx')->nullable();
            $table->string('khalti_transaction_id')->nullable();
            $table->string('status')->default('pending');
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
