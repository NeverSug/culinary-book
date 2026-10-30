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
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('cover_path')->nullable();
            $table->decimal('total_calories', 10, 2)->unsigned()->default(0);
            $table->decimal('total_proteins', 10, 2)->default(0);
            $table->decimal('total_fats', 10, 2)->default(0);
            $table->decimal('total_carbohydrates', 10, 2)->default(0);
            $table->decimal('total_weight', 10, 2)->unsigned()->default(0); // в граммах
            $table->decimal('calories_per_100g', 8, 2)->default(0);
            $table->decimal('proteins_per_100g', 8, 2)->default(0);
            $table->decimal('fats_per_100g', 8, 2)->default(0);
            $table->decimal('carbohydrates_per_100g', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
