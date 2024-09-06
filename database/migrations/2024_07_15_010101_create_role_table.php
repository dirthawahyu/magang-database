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
            $table->integer('priority');
            $table->timestamps();
        });

        DB::table('role')->insert([
            ['name' => 'Supervisor', 'priority' => 1],
            ['name' => 'Senior', 'priority' => 1],
            ['name' => 'Junior', 'priority' => 2],
            ['name' => 'Internship', 'priority' => 2],
            ['name' => 'part_time', 'priority' => 3],
            ['name' => 'magang', 'priority' => 3],
        ]);
        
    }

    public function down()
    {
        Schema::dropIfExists('role');
    }
}

