<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_requests', function (Blueprint $table) {
            $table->id();

            // shop owner personal info
            $table->string('owner_name');
            $table->string('email')->unique();
            $table->string('phone');

            // barber shop details
            $table->string('shop_name');
            $table->text('shop_address');
            $table->string('city');
            $table->string('district');
            $table->string('shop_phone')->nullable();

            // business info
            $table->string('pan_number')->nullable();
            $table->integer('number_of_barbers')->default(1);
            $table->text('services_offered');
            $table->text('description')->nullable();
            $table->string('shop_image')->nullable();

            // status tracking
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partner_requests');
    }
};
