<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipe extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'cover_path',
        'user_id',
        'total_calories',
        'total_proteins',
        'total_fats',
        'total_carbohydrates',
        'total_weight',
        'calories_per_100g',
        'proteins_per_100g',
        'fats_per_100g',
        'carbohydrates_per_100g',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('amount', 'unit')
            ->withTimestamps();
    }

    // Калорийность на 100 г готового блюда
    public function getCaloriesPer100gAttribute(): float
    {
        return $this->total_weight > 0
            ? round($this->total_calories / $this->total_weight * 100, 2)
            : 0;
    }

    // Пересчёт итогов после изменения состава

    public function recalculateNutrition(): void
    {
        $this->load('products');
        $totals = ['calories' => 0, 'proteins' => 0, 'fats' => 0, 'carbohydrates' => 0];
        $weight = 0;

        foreach ($this->products as $product) {
            $amount = $product->pivot->amount;

            if ($product->pivot->unit === 'piece') {
                $weightG = $amount * ($product->piece_weight ?? 0);
            } else {
                $weightG = $amount;
            }

            $factor = $weightG / 100;

            foreach ($totals as $field => $sum) {
                $totals[$field] = $sum + $product->$field * $factor;
            }

            $weight += $weightG;
        }

        $this->update([
            'total_calories'         => round($totals['calories'], 2),
            'total_proteins'         => round($totals['proteins'], 2),
            'total_fats'             => round($totals['fats'], 2),
            'total_carbohydrates'    => round($totals['carbohydrates'], 2),
            'total_weight'           => round($weight, 2),
            'calories_per_100g'      => $weight > 0 ? round($totals['calories'] / $weight * 100, 2) : 0,
            'proteins_per_100g'      => $weight > 0 ? round($totals['proteins'] / $weight * 100, 2) : 0,
            'fats_per_100g'          => $weight > 0 ? round($totals['fats'] / $weight * 100, 2) : 0,
            'carbohydrates_per_100g' => $weight > 0 ? round($totals['carbohydrates'] / $weight * 100, 2) : 0,
        ]);
    }

    public function convertToGrams(Product $product): float
    {
        return match ($product->pivot->unit) {
            'g' => $product->pivot->amount,
            'ml' => $product->pivot->amount, // упрощение: 1 мл ≈ 1 г
            'piece' => $product->pivot->amount * ($product->piece_weight ?? 0),
            default => 0,
        };
    }

// Вклад конкретного продукта в калорийность рецепта
    public function calculateProductCalories(Product $product): float
    {
        $grams = $this->convertToGrams($product);

        return round($product->calories * $grams / 100, 2);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'recipe_likes')->withTimestamps();
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getCoverUrlAttribute(): string
    {
        return $this->cover_path
            ? asset('storage/' . $this->cover_path)
            : asset('images/recipe-placeholder.jpg');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
