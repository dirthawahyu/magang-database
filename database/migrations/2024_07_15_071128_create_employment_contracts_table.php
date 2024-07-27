<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentContractsTable extends Migration
{
    public function up()
    {
        Schema::create('employment_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');
            $table->unsignedBigInteger('id_employee_group');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_position');
            $table->string('status_employee');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Magang', 'Kontrak', 'Full time', 'Canceled'])->default('Magang');
            $table->timestamps();
            $table->foreign('id_company')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('id_employee_group')->references('id')->on('employee_group')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_position')->references('id')->on('position')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('employment_contracts');
    }
}
