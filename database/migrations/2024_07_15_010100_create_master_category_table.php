<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMasterCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('master_category', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->timestamps();
        });

        DB::table('master_category')->insert([
            //Leave Category
            ['type' => 'leave', 'name' => 'Cuti Tahunan', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'leave', 'name' => 'Cuti Bulanan', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'leave', 'name' => 'Cuti Hamil', 'created_at' => now(), 'updated_at' => now()],

            //Payroll Header Category
            ['type' => 'payroll_header', 'name' => 'Montly Payroll', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'payroll_header', 'name' => 'THR', 'created_at' => now(), 'updated_at' => now()],

            //Payroll Line Category
            ['type' => 'payroll_line', 'name' => 'Gaji Pokok', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'payroll_line', 'name' => 'Perjalanan Dinas', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'payroll_line', 'name' => 'Potongan Cuti', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('master_category');
    }
}
