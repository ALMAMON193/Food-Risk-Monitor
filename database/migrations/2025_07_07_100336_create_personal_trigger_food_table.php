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
        Schema::create('personal_trigger_food', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('food_name');
            $table->string('serving_quantity')->nullable();
            $table->string('us_measurement')->nullable();
            $table->string('metric_measurement')->nullable();
            $table->string('fodmap_rating')->nullable();
            $table->string('food_category')->nullable();
            $table->string('bloating_risk_standard')->nullable();
            $table->string('bloating_risk_low')->nullable();
            $table->string('bloating_risk_medium')->nullable();
            $table->string('bloating_risk_high')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_trigger_food');
    }
};
