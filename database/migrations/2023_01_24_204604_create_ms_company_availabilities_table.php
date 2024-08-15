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
        Schema::create('ms_company_availabilities', function (Blueprint $table) {
            $table->id();
            $table->index('company_id');
            $table->foreignId('company_id');
            $table->foreign('company_id')->references('id')->on('ms_companies');
            $table->date('date');
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
        Schema::dropIfExists('ms_company_availabilities');
    }
};
