<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryExpenditureTable extends Migration
{
    public function up()
    {
        Schema::create('category_expenditure', function (Blueprint $table) {
            $table->id('id_category_expenditure');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_expenditure');
    }
}

