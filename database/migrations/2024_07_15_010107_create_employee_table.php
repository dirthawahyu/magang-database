<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEmployeeTable extends Migration
{
    public function up()
    {
        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_role');
            $table->unsignedBigInteger('id_employee_group');
            $table->unsignedBigInteger('id_company');
            $table->string('fcm_token')->nullable();
            $table->enum('status', ['Active', 'Not Active'])->default('Not Active');
            $table->enum('tax_status', ['Active', 'Not Active'])->default('Not Active'); // Replace with actual status values
            $table->integer('nip');
            $table->timestamps();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_role')->references('id')->on('role')->onDelete('cascade');
            $table->foreign('id_employee_group')->references('id')->on('employee_group')->onDelete('cascade');
            $table->foreign('id_company')->references('id')->on('company')->onDelete('cascade');
        });

        DB::table('employee')->insert([
            [
                'id_user' => 1, 
                'id_role' => 1, 
                'id_employee_group' => 1,
                'id_company' => 1, 
                'status' => 'Active',
                'tax_status' => 'Active',
                'nip' => 123456,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan data karyawan lain jika diperlukan
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('employee');
    }
}
