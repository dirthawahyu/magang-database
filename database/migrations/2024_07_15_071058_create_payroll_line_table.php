<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollLineTable extends Migration
{
    public function up()
    {
        Schema::create('payroll_line', function (Blueprint $table) {
            $table->id();
            $table->integer('id_payroll');
            $table->integer('id_salary_category');
            $table->integer('nominal');
            $table->string('note');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_line');
    }
}

