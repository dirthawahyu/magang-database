<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePayrollLineTable extends Migration
{
    public function up()
    {
        Schema::create('payroll_line', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_payroll_header');
            $table->unsignedBigInteger('id_master_category');
            $table->integer('nominal');
            $table->string('note');
            $table->timestamps();
            $table->foreign('id_payroll_header')->references('id')->on('payroll_header')->onDelete('cascade');
            $table->foreign('id_master_category')->references('id')->on('master_category')->onDelete('cascade');
        });

     
        DB::table('payroll_line')->insert([
            [
                'id_payroll_header' => 1, 
                'id_master_category' => 1,
                'nominal' => 2500000, 
                'note' => 'Basic salary',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('payroll_line');
    }
}
