<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->string('food_name');
            $table->string('serving_quantity');
            $table->string('unit');
            $table->string('us_measurement')->nullable();
            $table->string('metric_measurement')->nullable();
            $table->string('fodmap_rating');
            $table->string('fodmap_type')->nullable();

            // FODMAP triggers
            $table->boolean('fructose')->default(false);
            $table->boolean('lactose')->default(false);
            $table->boolean('sorbitol')->default(false);
            $table->boolean('mannitol')->default(false);
            $table->boolean('fructans')->default(false);
            $table->boolean('gos')->default(false);

            $table->string('food_category');
            $table->text('ibs_notes')->nullable();
            $table->string('dietary_tags')->nullable();

            // Diet flags
            $table->boolean('vegan')->default(false);
            $table->boolean('gluten_free')->default(false);
            $table->boolean('vegetarian')->default(false);

            $table->string('usda_match')->nullable();

            // Bloating‑risk 0‑10 scale
            $table->decimal('bloating_risk_standard', 3, 1)->default(0);
            $table->decimal('bloating_risk_low',      3, 1)->default(0);
            $table->decimal('bloating_risk_medium',   3, 1)->default(0);
            $table->decimal('bloating_risk_high',     3, 1)->default(0);

            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
