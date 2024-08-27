<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCategoryExpenditureTable extends Migration
{
    public function up()
    {
        Schema::create('category_expenditure', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        DB::table('category_expenditure')->insert([
              ['name' => 'Transportasi'],
              ['name' => 'Konsumsi'],
              ['name' => 'Hotel'],
              ['name' => 'DLL'],
          ]);
    }


 


    public function down()
    {
        Schema::dropIfExists('category_expenditure');
    }
}

