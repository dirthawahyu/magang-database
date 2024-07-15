<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveTable extends Migration
{
    public function up()
    {
        Schema::create('leave', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_category');
            $table->string('reason_for_leave');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyInteger('status'); // 0 = Pending, 1 = Approved, 2 = Declined, 3 = Canceled
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave');
    }
}
