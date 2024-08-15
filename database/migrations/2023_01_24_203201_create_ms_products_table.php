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
        Schema::create('ms_products', function (Blueprint $table) {
            $table->id();
            $table->index('type_id');
            $table->foreignId('type_id');
            $table->foreign('type_id')->references('id')->on('ms_product_types');
            $table->string('name');
            $table->string('capacity_info');
            $table->integer('duration');
            $table->string('image')->nullable();
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
        Schema::dropIfExists('ms_products');
    }
};
