<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentContractsTable extends Migration
{
    public function up()
    {
        Schema::create('employment_contracts', function (Blueprint $table) {
            $table->id('id_employment_contracts');
            $table->integer('id_company');
            $table->integer('id_employee_group');
            $table->integer('id_user');
            $table->integer('id_position');
            $table->string('status_employee');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status'); // 0 = Magang, 1 = Kontrak, 2 = Full Time, 3 = Freelance
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employment_contracts');
    }
}
