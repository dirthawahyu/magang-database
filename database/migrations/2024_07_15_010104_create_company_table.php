<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreateCompanyTable extends Migration
{
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_city');
            $table->string('pic');
            $table->string('company');
            $table->string('address');
            $table->timestamps();
            
            $table->foreign('id_city')->references('id')->on('city')->onDelete('cascade');
        });

        DB::table('company')->insert([
            ['id_city' => 1, 'pic' => 'Wildan', 'company' => 'PT. JAWA','address' => 'Jl Siwalankerto Permai No. 1'],
            ['id_city' => 2, 'pic' => 'Adit', 'company' => 'PT. JAWIR','address' => 'Jl Siwalankerto Permai No. 2'],
            ['id_city' => 3, 'pic' => 'Asep', 'company' => 'PT. JAWOR','address' => 'Jl Siwalankerto Permai No. 3'],
        ]);
    }

    public function down()
    {
        Schema::table('city', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['id_city']);
        });

        Schema::dropIfExists('company');
    }
}
