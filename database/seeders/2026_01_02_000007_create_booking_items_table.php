<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // nullable in case the service is deleted later
            $table->foreignId('service_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // snapshot fields — frozen at booking time so price/name changes don't affect history
            $table->string('service_name');
            $table->decimal('service_price', 8, 2);
            $table->unsignedInteger('service_duration');
            $table->string('category_name')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};
