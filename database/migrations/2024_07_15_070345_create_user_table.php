<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('nik');
            $table->string('city');
            $table->string('address');
            $table->string('religion');
            $table->date('birth_date');
            $table->string('phone_number');
            $table->string('email');
            $table->string('password');
            $table->tinyInteger('gender'); // 0 = Tidak Diketahui, 1 = Perempuan, 2 = Laki Laki
            $table->text('profile_photo')->nullable();
            $table->text('ktp_photo')->nullable();
            $table->integer('no_rekening');
            $table->string('blacklist_reason')->nullable();
            $table->integer('block_date')->nullable();
            $table->tinyInteger('marital_status'); // 0 = Lajang, 1 = Menikah, 2 = Cerai
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user');
    }
}
