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
            $table->unsignedBigInteger('id_employment_contract');
            $table->unsignedBigInteger('id_master_category');
            $table->integer('nominal');
            $table->timestamps();
            $table->foreign('id_employment_contract')->references('id')->on('employment_contracts')->onDelete('cascade');
            $table->foreign('id_master_category')->references('id')->on('master_category')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employment_contract_detail');
    }
}
