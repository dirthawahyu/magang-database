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
            $table->integer('id_pegawai');
            $table->integer('id_payroll_category');
            $table->date('payroll_date');
            $table->integer('net_amount');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_header');
    }
}

