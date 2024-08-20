<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMasterCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('leave_category', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('limit');
            $table->string('name');
            $table->timestamps();
        });

        DB::table('leave_category')->insert([
            //Leave Category
            ['type' => 'leave', 'limit' => '10 days', 'name' => 'Cuti Tahunan', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'leave', 'limit' => '5 days', 'name' => 'Cuti Bulanan', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'leave', 'limit' => '11 days', 'name' => 'Cuti Hamil', 'created_at' => now(), 'updated_at' => now()],

        ]);
    }

    public function down()
    {
        Schema::dropIfExists('leave_category');
    }
}
