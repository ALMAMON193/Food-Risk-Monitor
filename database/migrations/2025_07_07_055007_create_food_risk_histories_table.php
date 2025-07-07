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
        Schema::create('food_risk_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('food_name');
            $table->float('serving_quantity');
            $table->string('us_measurement');
            $table->string('metric_measurement');
            $table->string('meal_type')->nullable();
            $table->decimal('risk_score', 5, 2);
            $table->string('risk_label');
            $table->timestamps();

            // Unique index to prevent duplicate user_id + food_name rows
            $table->unique(['user_id', 'food_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_risk_histories');
    }
};
