<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
    }

    public function down()
    {
        Schema::dropIfExists('employee_group');
    }
}

