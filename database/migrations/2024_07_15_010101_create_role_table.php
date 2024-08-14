<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreateRoleTable extends Migration
{
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('role')->insert([
            ['name' => 'Supervisor',],
            ['name' => 'Senior',],
            ['name' => 'Junior',],
            ['name' => 'Internship',],
        ]);
        
    }

    public function down()
    {
        Schema::dropIfExists('role');
    }
}

