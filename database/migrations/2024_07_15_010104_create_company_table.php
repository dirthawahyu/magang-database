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
            $table->string('name');
            $table->timestamps();            
        });

        DB::table('company')->insert([
            ['name' => 'PT Surya'],
            ['name' => 'PT Jaya'],
            ['name' => 'PT Anugrah'],
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
