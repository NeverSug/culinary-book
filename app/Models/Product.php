<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'calories',
        'proteins',
        'fats',
        'carbohydrates',
        'piece_weight',
        'unit',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class);
    }
    public function getUnitLabelAttribute(): string
    {
        return match ($this->unit) {
            'g'     => 'г',
            'ml'    => 'мл',
            'piece' => 'шт',
            default => $this->unit,
        };
    }
}
