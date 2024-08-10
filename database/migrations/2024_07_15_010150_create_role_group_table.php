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
        Schema::create('role_group', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_role_name');
            $table->unsignedBigInteger('id_role');
            $table->timestamps();
            $table->foreign('id_role_name')->references('id')->on('role_name')->onDelete('cascade');
            $table->foreign('id_role')->references('id')->on('role')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_group');
    }
};
