<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // seed all default categories including the ones added later
        DB::table('service_categories')->insert([
            ['name' => 'Haircut',          'slug' => 'haircut',          'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Beard',            'slug' => 'beard',            'sort_order' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hair Style',       'slug' => 'hair-style',       'sort_order' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hair Colour',      'slug' => 'hair-colour',      'sort_order' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Facial & Skin',    'slug' => 'facial-skin',      'sort_order' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hair Treatment',   'slug' => 'hair-treatment',   'sort_order' => 6, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('service_categories');
    }
};
