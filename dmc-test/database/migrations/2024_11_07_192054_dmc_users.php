<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dmc_users', function (Blueprint $table) {
            $table->id();
            $table->string("uid")->nullable();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->string("email")->unique();
            $table->string("password");
            $table->string("phone")->nullable();
            $table->string("phone_2")->nullable();
            $table->string("postal_code")->nullable();
            //En el ejemplo esto esta como string, no una date, raro.
            $table->string("birth_date")->nullable();
            $table->string("gender", 1)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dmc_users');
    }
};
