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
        Schema::create('ms_unit_availabilities', function (Blueprint $table) {
            $table->id();
            $table->index('company_id');
            $table->index('unit_id');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained()->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('ms_companies');
            $table->foreign('unit_id')->references('id')->on('ms_units');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
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
        Schema::dropIfExists('ms_unit_availabilities');
    }
};
