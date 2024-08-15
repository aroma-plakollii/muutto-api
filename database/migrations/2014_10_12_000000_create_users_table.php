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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            $table->index('role_id');
            $table->foreignId('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->index('ms_company_id');
            $table->foreignId('ms_company_id');
            $table->foreign('ms_company_id')->references('id')->on('ms_companies');
            $table->index('mb_company_id');
            $table->foreignId('mb_company_id');
            $table->foreign('mb_company_id')->references('id')->on('mb_companies');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
