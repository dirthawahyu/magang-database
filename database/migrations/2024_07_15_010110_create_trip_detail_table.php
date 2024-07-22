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
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_business_trip');
            $table->timestamps();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_business_trip')->references('id')->on('business_trip')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_detail');
    }
}
