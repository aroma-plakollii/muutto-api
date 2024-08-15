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
        Schema::create('ms_unit_product_prices', function (Blueprint $table) {
            $table->id();
            $table->index('unit_id');
            $table->index('product_id');
            $table->foreignId('unit_id');
            $table->foreignId('product_id');
            $table->foreign('unit_id')->references('id')->on('ms_units');
            $table->foreign('product_id')->references('id')->on('ms_products');
            $table->decimal('price', 10, 2);
            $table->integer('saturday_price');
            $table->integer('sunday_price');
            $table->decimal('discount_price', 10, 2);
            $table->decimal('price_per_m2', 10, 2);
            $table->integer('included_m2');
            $table->integer('no_elevator');
            $table->integer('small_elevator');
            $table->integer('big_elevator');
            $table->integer('new_building');
            $table->decimal('price_per_km', 10, 2);
            $table->integer('included_km');
            $table->decimal('basement_storage_price_per_m2', 10, 2);
            $table->integer('included_m2_basement_storage');
            $table->decimal('roof_storage_price_per_m2', 10, 2);
            $table->integer('included_m2_roof_storage');
            $table->integer('included_meters_outdoor');
            $table->decimal('outdoor_price_per_meter', 10, 2);
            $table->string('description');
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
        Schema::dropIfExists('ms_company_product_prices');
    }
};
