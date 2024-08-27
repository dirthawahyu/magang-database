<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePlanningRealizationHeaderTable extends Migration
{
    public function up()
    {
        Schema::create('planning_realization_header', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_business_trip');
            $table->unsignedBigInteger('id_category_expenditure');
            $table->string('keterangan');
            $table->string('nominal_planning');
            $table->string('nominal_realization');
            $table->timestamps();
            $table->foreign('id_business_trip')->references('id')->on('business_trip')->onDelete('cascade');
            $table->foreign('id_category_expenditure')->references('id')->on('category_expenditure')->onDelete('cascade');
        });

        DB::table('planning_realization_header')->insert([
            [
                'id_business_trip' => 1,
                'id_category_expenditure' => 1, 
                'keterangan' => 'Meeting with clients',
                'nominal_planning' => '5000', 
                'nominal_realization' => '4500', 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('planning_realization_header');
    }
}

