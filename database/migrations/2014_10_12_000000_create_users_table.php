<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('nik')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('religion')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('username')->unique();
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
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });
        
        DB::table('users')->insert([
            [
                'first_name' => 'Test',
                'last_name' => 'Name',
                'nik' => 123,
                'city' => 'Jakarta',
                'address' => 'Jl. Sudirman No.1',
                'religion' => 'Islam',
                'birth_date' => '1990-01-01',
                'phone_number' => '08123456789',
                'username' => 'testname',
                'email' => 'test@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'gender_status' => 'Laki-Laki',
                'profile_photo' => 'https://i.pinimg.com/564x/7c/af/16/7caf16ffec532599adf6c6a9ee863754.jpg',
                'ktp_photo' => 'https://i.pinimg.com/564x/52/b4/e7/52b4e7692ae99ccd5fc76d0fe1948de3.jpg',
                'no_rekening' => 654321,
                'blacklist_reason' => 'Nyuri motor',
                'block_date' => 1,
                'marital_status' => 'Lajang',
                'remember_token' => \Illuminate\Support\Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
