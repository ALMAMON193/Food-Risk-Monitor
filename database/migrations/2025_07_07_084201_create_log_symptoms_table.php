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
        Schema::create('log_symptoms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('food_name');
            $table->unsignedTinyInteger('bloating')->nullable();
            $table->unsignedTinyInteger('gas')->nullable();
            $table->unsignedTinyInteger('pain')->nullable();
            $table->unsignedTinyInteger('stool_issues')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_symptoms');
    }
};
