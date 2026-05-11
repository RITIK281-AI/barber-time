<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barber_shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('shop_image')->nullable();
            $table->string('shop_document')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('phone')->nullable();
            $table->time('opening_time')->default('09:00:00');
            $table->time('closing_time')->default('18:00:00');
            $table->string('owner_name')->nullable();
            // suspended added to status enum from the start
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->string('email')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('business_license_number', 100)->nullable();
            $table->date('business_registration_date')->nullable();
            $table->unsignedInteger('shop_area_sqft')->nullable();
            $table->integer('number_of_barbers')->default(1);
            $table->unsignedInteger('number_of_chairs')->default(1);
            $table->unsignedInteger('years_of_experience')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->text('services_offered')->nullable();
            $table->text('description')->nullable();
            $table->decimal('average_rating', 2, 1)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->text('admin_remarks')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        // add FK from users to barber_shops now that barber_shops exists
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('barber_shop_id')
                  ->references('id')
                  ->on('barber_shops')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['barber_shop_id']);
        });

        Schema::dropIfExists('barber_shops');
    }
};
