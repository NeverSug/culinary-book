<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealPlan extends Model
{
    protected $fillable = ['user_id', 'recipe_id', 'day_of_week'];

    public const DAYS = [
        1 => 'Понедельник', 2 => 'Вторник', 3 => 'Среда',
        4 => 'Четверг',     5 => 'Пятница', 6 => 'Суббота', 7 => 'Воскресенье',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
