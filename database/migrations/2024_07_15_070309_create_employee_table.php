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
            $table->integer('id_user');
            $table->integer('id_role');
            $table->integer('id_employee_group');
            $table->integer('id_position');
            $table->integer('id_company');
            $table->tinyInteger('status'); // 0 = Active, 1 = Not Active
            $table->enum('id_tax_status', ['status1', 'status2']); // Replace with actual status values
            $table->integer('nip');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee');
    }
}
