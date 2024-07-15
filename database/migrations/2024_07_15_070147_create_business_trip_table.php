<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTripTable extends Migration
{
    public function up()
    {
        Schema::create('business_trip', function (Blueprint $table) {
            $table->id('id_business_trip');
            $table->string('note');
            $table->text('photo_document');
            $table->tinyInteger('status'); // 0 = Draft, 1 = On Progress, 2 = Completed, 3 = Canceled
            $table->integer('company');
            $table->integer('phone_number');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('extend_day');
            $table->string('pic_company');
            $table->string('pic_role');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_trip');
    }
}
