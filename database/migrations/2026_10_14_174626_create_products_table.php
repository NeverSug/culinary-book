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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('calories', 8, 2)->unsigned();
            $table->decimal('proteins', 8, 2)->unsigned()->default(0);
            $table->decimal('fats', 8, 2)->unsigned()->default(0);
            $table->decimal('carbohydrates', 8, 2)->unsigned()->default(0);
            $table->decimal('piece_weight', 8, 2)->unsigned()->nullable();
            $table->string('unit', 10)->default('g'); // g | ml | piece
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
