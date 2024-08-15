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
        Schema::create('mb_prices', function (Blueprint $table) {
            $table->id();
            $table->index('company_id');
            $table->foreignId('company_id');
            $table->foreign('company_id')->references('id')->on('mb_companies');
            $table->decimal('price_per_day', 5, 2)->default(0);
            $table->decimal('price_per_package', 5, 2)->default(0);
            $table->decimal('price_per_km',5, 2)->default(0);
            $table->decimal('booking_price',5, 2)->default(0);
            $table->decimal('additional_price',5, 2)->default(0);
            $table->decimal('additional_package_price',5, 2)->default(0);
            $table->string('package_days')->default(0);
            $table->string('included_km')->default(0);
            $table->string('min_boxes');
            $table->string('type')->nullable();
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
        Schema::dropIfExists('mb_prices');
    }
};
