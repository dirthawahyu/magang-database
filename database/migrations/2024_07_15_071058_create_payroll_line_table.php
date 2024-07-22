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
            $table->unsignedBigInteger('id_payroll');
            $table->unsignedBigInteger('id_master_category');
            $table->integer('nominal');
            $table->string('note');
            $table->timestamps();
            $table->foreign('id_payroll')->references('id')->on('payroll')->onDelete('cascade');
            $table->foreign('id_master_category')->references('id')->on('master_category')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_line');
    }
}

