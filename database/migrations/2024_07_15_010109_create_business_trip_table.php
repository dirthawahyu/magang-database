<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBusinessTripTable extends Migration
{
    public function up()
    {
        Schema::create('business_trip', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company');
            $table->string('note');
            $table->text('photo_document')->nullable();
            $table->enum('status', ['Draft', 'On Progress', 'Completed', 'Canceled'])->default('Draft');
            $table->integer('phone_number');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('extend_day');
            $table->timestamps();
            $table->foreign('id_company')->references('id')->on('company')->onDelete('cascade');
        });

        // Insert sample data into the 'business_trip' table
        DB::table('business_trip')->insert([
            [
                'id_company' => 1,
                'note' => 'Business trip to New York for client meeting',
                'photo_document' => 'https://i.pinimg.com/736x/3c/a3/f0/3ca3f02e1ec3a6d2d30a6558b97add06.jpg',
                'status' => 'Draft',
                'phone_number' => 1234567890,
                'start_date' => '2024-08-15',
                'end_date' => '2024-09-20',
                'extend_day' => 2,
                'pic_company' => 'Wildan',
                'pic_role' => 'Project Manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('business_trip');
    }
}
