<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTripTable extends Migration
{
    public function up()
    {
        Schema::create('business_trip', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_company_city');
            $table->string('note');
            $table->text('photo_document')->nullable();
            $table->enum('status', ['Draft', 'On Progress', 'Completed', 'Canceled'])->default('Draft');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('departure_from');
            $table->integer('extend_day');
            $table->timestamps();
            $table->foreign('id_company_city')->references('id')->on('company_city')->onDelete('cascade');
        });

        // Insert sample data into the 'business_trip' table
        DB::table('business_trip')->insert([
            [
                'id_company_city' => 1,
                'note' => 'Business trip to New York for client meeting',
                'photo_document' => 'https://i.pinimg.com/736x/3c/a3/f0/3ca3f02e1ec3a6d2d30a6558b97add06.jpg',
                'status' => 'Draft',
                'start_date' => '2024-08-15',
                'end_date' => '2024-09-20',
                'departure_from' => 'Aceh',
                'extend_day' => 2,
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
