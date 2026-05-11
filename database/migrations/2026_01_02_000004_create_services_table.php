<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barber_shop_id')
                  ->constrained('barber_shops')
                  ->cascadeOnDelete();
            // category added from the start — nullable so old services without a category still work
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('service_categories')
                  ->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('duration');
            // final status enum — active/inactive (not the old available/unavailable)
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
