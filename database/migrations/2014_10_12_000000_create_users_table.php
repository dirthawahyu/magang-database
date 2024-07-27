<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('nik')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('religion')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('gender_status', ['Tidak Diketahui', 'Laki-Laki', 'Perempuan'])->default('Tidak Diketahui');
            $table->text('profile_photo');
            $table->text('ktp_photo')->nullable();
            $table->integer('no_rekening')->nullable();
            $table->string('blacklist_reason')->nullable();
            $table->integer('block_date')->nullable();
            $table->enum('marital_status', ['Lajang', 'Menikah', 'Cerai'])->default('lajang');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
