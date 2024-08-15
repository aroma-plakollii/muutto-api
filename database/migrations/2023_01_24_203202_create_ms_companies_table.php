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
        Schema::create('ms_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('business_number');
            $table->string('description')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->date('register_date');
            $table->date('termination_date');
            $table->string('api_key');
            $table->string('private_key');
            $table->string('secret_key');
            $table->boolean('is_featured');
            $table->boolean('status');
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
        Schema::dropIfExists('ms_companies');
    }
};
