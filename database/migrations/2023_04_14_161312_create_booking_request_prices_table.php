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
        Schema::create('booking_request_prices', function (Blueprint $table) {
            $table->id();
            $table->index('booking_request_id');
            $table->foreignId('booking_request_id');
            $table->foreign('booking_request_id')->references('id')->on('booking_requests');
            $table->index('company_id');
            $table->foreignId('company_id');
            $table->foreign('company_id')->references('id')->on('ms_companies');
            $table->decimal('price', 10, 2);
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
        Schema::dropIfExists('booking_request_prices');
    }
};
