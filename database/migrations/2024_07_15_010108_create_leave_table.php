<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateLeaveTable extends Migration
{
    public function up()
    {
        Schema::create('leave', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_master_category');
            $table->string('reason_for_leave');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['Pending', 'Approved', 'Declined', 'Canceled'])->default('Pending');
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_master_category')->references('id')->on('master_category')->onDelete('cascade');
        });

        // Insert default data into the 'leave' table
        DB::table('leave')->insert([
            [
                'id_user' => 1,
                'id_master_category' => 1,
                'reason_for_leave' => 'Liburan',
                'start_date' => '2024-08-10', 
                'end_date' => '2024-08-15',   
                'status' => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::table('leave', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['id_user']);
            $table->dropForeign(['id_master_category']);
        });

        Schema::dropIfExists('leave');
    }
}
