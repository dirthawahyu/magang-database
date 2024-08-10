<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEmploymentContractsTable extends Migration
{
    public function up()
    {
        Schema::create('employment_contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');
            $table->unsignedBigInteger('id_employee_group');
            $table->unsignedBigInteger('id_user');
            $table->string('status_employee');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Magang', 'Kontrak', 'Full time', 'Canceled'])->default('Magang');
            $table->timestamps();
            $table->foreign('id_company')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('id_employee_group')->references('id')->on('employee_group')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });

        DB::table('employment_contracts')->insert([
            [
                'id_company' => 1, 
                'id_employee_group' => 1, 
                'id_user' => 1,
                'status_employee' => 'Active',
                'start_date' => '2024-08-01', 
                'end_date' => '2025-08-01', 
                'status' => 'Kontrak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('employment_contracts');
    }
}
