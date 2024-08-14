<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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

        DB::table('payroll_header')->insert([
            [
                'id_employee' => 1, 
                'id_master_category' => 1, 
                'payroll_date' => '2024-08-31',
                'net_amount' => 5000000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('payroll_header');
    }
}
