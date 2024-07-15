<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningRealizationHeaderTable extends Migration
{
    public function up()
    {
        Schema::create('planning_realization_header', function (Blueprint $table) {
            $table->id('id_planning_realization');
            $table->integer('id_business_trip');
            $table->integer('id_pengeluaran_kategori');
            $table->string('keterangan');
            $table->string('nominal_planning');
            $table->string('nominal_realization');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('planning_realization_header');
    }
}

