<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentContractDetailTable extends Migration
{
    public function up()
    {
        Schema::create('employment_contract_detail', function (Blueprint $table) {
            $table->id('id_employment_contract_detail');
            $table->integer('id_employment_contract');
            $table->integer('id_salary_category');
            $table->integer('nominal');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employment_contract_detail');
    }
}
