<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('checkin_activity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->dateTime('time');
            $table->integer('type');
            $table->integer('status');
            $table->float('latitude');
            $table->float('longitude');
            $table->text('photo')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });

        DB::table('checkin_activity')->insert([
            ['id_user' => 1, 'time' => '2024-08-10-07-24', 'type' => 0, 'status' => 0, 'latitude' => -7.340671, 'longitude' => 112.7364161,
            ],
            ['id_user' => 1, 'time' => '2024-08-10-17-00', 'type' => 1, 'status' => 0, 'latitude' => -7.340671, 'longitude' => 112.7364161,
            ],
            ['id_user' => 1, 'time' => '2024-08-10-07-24', 'type' => 0, 'status' => 1, 'latitude' => -7.340671, 'longitude' => 112.7364161,
            ],
            ['id_user' => 1, 'time' => '2024-08-10-17-00', 'type' => 1, 'status' => 1, 'latitude' => -7.340671, 'longitude' => 112.7364161,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkin_activity');
    }
};