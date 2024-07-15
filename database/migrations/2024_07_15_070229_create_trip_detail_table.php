<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripDetailTable extends Migration
{
    public function up()
    {
        Schema::create('trip_detail', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_business_trip');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_detail');
    }
}
