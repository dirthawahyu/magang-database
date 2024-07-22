<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollHeaderTable extends Migration
{
    public function up()
    {
        Schema::create('payroll_header', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_employee');
            $table->unsignedBigInteger('id_master_category');
            $table->date('payroll_date');
            $table->integer('net_amount');
            $table->timestamps();
            $table->foreign('id_employee')->references('id')->on('employee')->onDelete('cascade');
            $table->foreign('id_master_category')->references('id')->on('master_category')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_header');
    }
}

