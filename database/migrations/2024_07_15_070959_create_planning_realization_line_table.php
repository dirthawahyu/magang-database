<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePlanningRealizationLineTable extends Migration
{
    public function up()
    {
        Schema::create('planning_realization_line', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_planning_realization_header');
            $table->text('photo_proof');
            $table->string('keterangan');
            $table->timestamps();
            $table->foreign('id_planning_realization_header')->references('id')->on('planning_realization_header')->onDelete('cascade');
        });

        DB::table('planning_realization_line')->insert([
            [
                'id_planning_realization_header' => 1, 
                'photo_proof' => 'https://i.pinimg.com/736x/2f/c0/71/2fc0718fc111c266a5850e2f11100bea.jpg', 
                'keterangan' => 'Proof of payment for client dinner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('planning_realization_line');
    }
}
