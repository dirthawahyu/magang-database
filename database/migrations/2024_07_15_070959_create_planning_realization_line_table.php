<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningRealizationLineTable extends Migration
{
    public function up()
    {
        Schema::create('planning_realization_line', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_planning_realization');
            $table->text('photo_proof');
            $table->string('keterangan');
            $table->timestamps();
            $table->foreign('id_planning_realization')->references('id')->on('planning_realization')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('planning_realization_line');
    }
}
