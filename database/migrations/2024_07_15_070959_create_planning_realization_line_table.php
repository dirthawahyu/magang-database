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
            $table->integer('id_planning_realization');
            $table->text('photo_proof');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('planning_realization_line');
    }
}
