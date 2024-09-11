<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('company_city', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');
            $table->unsignedBigInteger('id_city');
            $table->string('address');
            $table->string('pic');
            $table->string('pic_role');
            $table->string('pic_phone');
            $table->timestamps();
            
            $table->foreign('id_company')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('id_city')->references('id')->on('city')->onDelete('cascade');
        });

        DB::table('company_city')->insert([
            ['id_company' => 1, 'id_city' => 1, 'address' => 'Jl Siwalankerto Permai No. 1' , 'pic' => 'Wildan', 'pic_role' => 'senior','pic_phone' => '0895111111'],
            ['id_company' => 2, 'id_city' => 2, 'address' => 'Jl Siwalankerto Permai No. 2' , 'pic' => 'Adit', 'pic_role' => 'junior','pic_phone' => '0895222222'],
            ['id_company' => 3, 'id_city' => 3, 'address' => 'Jl Siwalankerto Permai No. 3' , 'pic' => 'Asep', 'pic_role' => 'hrd','pic_phone' => '0895333333'],
        ]);
    }

    public function down()
    {
        // Schema::table('company', function (Blueprint $table) {
        //     // Drop foreign key constraints
        //     $table->dropForeign(['id_company']);
        // });
        // Schema::table('city', function (Blueprint $table) {
        //     // Drop foreign key constraints
        //     $table->dropForeign(['id_city']);
        // });

        Schema::dropIfExists('company_city');
    }
};
