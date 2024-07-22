<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTable extends Migration
{
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_role');
            $table->unsignedBigInteger('id_employee_group');
            $table->unsignedBigInteger('id_position');
            $table->unsignedBigInteger('id_company');
            $table->enum('status', ['Active', 'Not Active'])->default('Not Active');
            $table->enum('tax_status', ['status1', 'status2']); // Replace with actual status values
            $table->integer('nip');
            $table->timestamps();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_role')->references('id')->on('role')->onDelete('cascade');
            $table->foreign('id_employee_group')->references('id')->on('employee_group')->onDelete('cascade');
            $table->foreign('id_position')->references('id')->on('position')->onDelete('cascade');
            $table->foreign('id_company')->references('id')->on('company                ')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee');
    }
}
