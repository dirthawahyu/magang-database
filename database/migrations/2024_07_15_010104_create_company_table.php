<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
