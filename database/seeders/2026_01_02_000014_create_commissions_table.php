<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            // setting key e.g. "commission_rate"
            $table->string('key')->unique();
            // setting value e.g. "10"
            $table->string('value')->nullable();
            $table->timestamps();
        });

        // seed default commission rate as 10%
        DB::table('commissions')->insert([
            'key'        => 'commission_rate',
            'value'      => '10',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
