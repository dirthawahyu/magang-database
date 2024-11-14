<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreateCompanyTable extends Migration
{
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('latitude');
            $table->float('longitude');
            $table->timestamps();
        });

        DB::table('company')->insert([
            ['name' => 'PT Tata Niaga', 'latitude' => -7.2555061, 'longitude' => 112.6700275],
            ['name' => 'PT Surya', 'latitude' => 37.77493, 'longitude' => -122.41942],
            ['name' => 'PT Jaya', 'latitude' => -7.340671, 'longitude' => 112.7364161],
        ]);
    }

    public function down()
    {
        // Schema::table('city', function (Blueprint $table) {
        //     // Drop foreign key constraints
        //     $table->dropForeign(['id_city']);
        // });

        Schema::dropIfExists('company');
    }
}