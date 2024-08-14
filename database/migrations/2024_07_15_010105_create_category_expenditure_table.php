<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCategoryExpenditureTable extends Migration
{
    public function up()
    {
        Schema::create('category_expenditure', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        DB::table('role_name')->insert([
              ['name' => 'Salaries and Wages'],
              ['name' => 'Rent and Utilities'],
              ['name' => 'Raw Materials'],
              ['name' => 'Production Costs'],
              ['name' => 'Advertising and Promotion'],
          ]);
    }


 


    public function down()
    {
        Schema::dropIfExists('category_expenditure');
    }
}

