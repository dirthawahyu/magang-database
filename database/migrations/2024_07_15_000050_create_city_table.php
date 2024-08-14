<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCityTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('city', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('city')->insert([
            ['name' => 'Jakarta', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Surabaya', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bandung', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Medan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Semarang', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city');
    }
}
