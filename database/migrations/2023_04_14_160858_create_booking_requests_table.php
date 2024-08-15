<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_requests', function (Blueprint $table) {
            $table->id();
            $table->index('product_id');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('ms_products');
            $table->string('booking_number');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('start_address');
            $table->string('end_address');
            $table->string('start_door_number');
            $table->string('end_door_number');
            $table->string('start_door_code');
            $table->string('end_door_code');
            $table->string('start_floor');
            $table->string('end_floor');
            $table->string('start_elevator');
            $table->string('end_elevator');
            $table->string('start_outdoor_distance');
            $table->string('end_outdoor_distance');
            $table->string('start_storage');
            $table->string('end_storage');
            $table->string('start_storage_m2');
            $table->string('end_storage_m2');
            $table->string('start_storage_floor');
            $table->string('end_storage_floor');
            $table->string('start_square_meters');
            $table->string('end_square_meters');
            $table->string('code');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_requests');
    }
};
