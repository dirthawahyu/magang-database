<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTripTable extends Migration
{
    public function up()
    {
        Schema::create('business_trip', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');
            $table->string('note');
            $table->text('photo_document');
            $table->enum('status', ['Draft', 'On Progress', 'Completed', 'Canceled'])->default('Draft');
            $table->integer('phone_number');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('extend_day');
            $table->string('pic_company');
            $table->string('pic_role');
            $table->timestamps();
            $table->foreign('id_company')->references('id')->on('company')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_trip');
    }
}
