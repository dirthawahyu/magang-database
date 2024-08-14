<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEmploymentContractDetailTable extends Migration
{
    public function up()
    {
        Schema::create('employment_contract_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_employment_contract');
            $table->unsignedBigInteger('id_master_category');
            $table->integer('nominal');
            $table->timestamps();
            $table->foreign('id_employment_contract')->references('id')->on('employment_contracts')->onDelete('cascade');
            $table->foreign('id_master_category')->references('id')->on('master_category')->onDelete('cascade');
        });

        DB::table('employment_contract_detail')->insert([
            [
                'id_employment_contract' => 1,
                'id_master_category' => 1,
                'nominal' => 7000000, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('employment_contract_detail');
    }
}

