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
        Schema::create('ms_units', function (Blueprint $table) {
            $table->id();
            $table->index('company_id');
            $table->foreignId('company_id');
            $table->foreign('company_id')->references('id')->on('ms_companies');
            $table->index('region_id');
            $table->foreignId('region_id');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->string('name')->nullable();
            $table->string('address');
            $table->decimal('price', 10, 2);
            $table->integer('persons');
            $table->string('capacity')->nullable();
            $table->boolean('availability')->default(1);
            $table->integer('max_shift');
            $table->string('image')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
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
        Schema::dropIfExists('ms_units');
    }
};
