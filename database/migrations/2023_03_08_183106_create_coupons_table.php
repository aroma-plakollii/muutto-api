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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->index('company_id');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('ms_companies');
            $table->string('code');
            $table->decimal('price', 8, 2);
            $table->integer('available_usages')->nullable();
            $table->integer('used')->default(0);
            $table->boolean('status')->default(1);
            $table->boolean('is_percentage')->default(0);
            $table->boolean('is_unlimited')->default(0);
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
        Schema::dropIfExists('coupons');
    }
};
