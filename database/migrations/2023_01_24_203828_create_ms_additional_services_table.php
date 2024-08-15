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
        Schema::create('ms_additional_services', function (Blueprint $table) {
            $table->id();
            $table->index('booking_id');
            $table->foreignId('booking_id');
            $table->foreign('booking_id')->references('id')->on('ms_bookings');
            $table->decimal('price', 10, 2);
            $table->string('description');
            $table->string('payment_number');
            $table->string('payment_status');
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
        Schema::dropIfExists('ms_additional_services');
    }
};
