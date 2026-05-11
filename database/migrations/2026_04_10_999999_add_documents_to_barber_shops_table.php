<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barber_shops', function (Blueprint $table) {
            $table->renameColumn('shop_document', 'shop_license');
            $table->string('registration_document')->nullable()->after('shop_image');
            $table->string('tax_clearance_document')->nullable()->after('registration_document');
        });
    }

    public function down(): void
    {
        Schema::table('barber_shops', function (Blueprint $table) {
            $table->renameColumn('shop_license', 'shop_document');
            $table->dropColumn(['registration_document', 'tax_clearance_document']);
        });
    }
};
