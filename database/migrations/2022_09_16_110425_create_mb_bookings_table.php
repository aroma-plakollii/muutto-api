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
        Schema::create('mb_bookings', function (Blueprint $table) {
            $table->id();
            $table->index('company_id');
            $table->foreignId('company_id');
            $table->foreign('company_id')->references('id')->on('mb_companies');
            $table->string('booking_number');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('start_address')->nullable();
            $table->string('end_address')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('end_date_old')->nullable();
            $table->decimal('price',10, 2);
            $table->decimal('start_price',10, 2);
            $table->decimal('end_price',10, 2);
            $table->decimal('rent_price',10, 2);
            $table->string('type');
            $table->integer('quantity');
            $table->string('start_door_number')->nullable();
            $table->string('end_door_number')->nullable();
            $table->string('start_door_code')->nullable();
            $table->string('end_door_code')->nullable();
            $table->string('start_comment')->nullable();
            $table->string('end_comment')->nullable();
            $table->string('payment_status');
            $table->string('progress_status');
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
        Schema::dropIfExists('mb_bookings');
    }
};
