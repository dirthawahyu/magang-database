<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreateEmployeeGroupTable extends Migration
{
    public function up()
    {
        Schema::create('employee_group', function (Blueprint $table) {
            $table->id();
            $table->integer('code');
            $table->string('basic_salary');
            $table->timestamps();
        });

        DB::table('employee_group')->insert([
            ['code' => '6601', 'basic_salary' => 'Rp.10000'],
            ['code' => '6602', 'basic_salary' => 'Rp.10000'],
            ['code' => '6003', 'basic_salary' => 'Rp.40000'],
            ['code' => '6004', 'basic_salary' => 'Rp.60000'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('employee_group');
    }
}

