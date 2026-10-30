<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealPlanController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'recipe_id' => 'required|integer|exists:recipes,id',
            'day_of_week' => 'required|integer|between:1,7',
        ]);

        $plan = MealPlan::where('user_id', Auth::id())
            ->where('recipe_id', $data['recipe_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->first();

        if (!$plan) {
            $plan = MealPlan::create([
                'user_id' => Auth::id(),
                'recipe_id' => $data['recipe_id'],
                'day_of_week' => $data['day_of_week'],
            ]);
        }

        $message = $plan->wasRecentlyCreated
            ? 'Рецепт «' . $plan->recipe->name . '» добавлен на ' . MealPlan::DAYS[$plan->day_of_week] . '.'
            : 'Этот рецепт уже стоит на ' . MealPlan::DAYS[$plan->day_of_week] . '.';

        if ($request->expectsJson()) {
            return response()->json([
                'planId' => $plan->id,
                'recipeId' => $plan->recipe_id,
                'recipe' => $plan->recipe->name,
                'day' => $plan->day_of_week,
                'message' => $message,
            ]);
        }

        return back()->with('status', $message);
    }

    public function destroy(Request $request, MealPlan $mealPlan)
    {
        abort_unless($mealPlan->user_id === Auth::id(), 403);

        $mealPlan->delete();

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('status', 'Рецепт убран из расписания.');
    }

    public function index()
    {
        $user = Auth::user();

        $plans = $user->mealPlans()
            ->with('recipe')
            ->get()
            ->groupBy('day_of_week');

        $likedRecipes = $user->likes()
            ->orderBy('name')
            ->get();

        return view('plan.index', compact('plans', 'likedRecipes'));
    }
    public function shopping()
    {
        $user = Auth::user();

        $plans = $user->mealPlans()
            ->with('recipe.products')
            ->get();

        $items = [];

        foreach ($plans as $plan) {
            foreach ($plan->recipe->products as $product) {
                if (!isset($items[$product->id])) {
                    $items[$product->id] = [
                        'name'   => $product->name,
                        'amount' => 0,
                        'days'   => [],
                        'unit' => $product->pivot->unit ?? $product->unit ?? ''
                    ];
                }

                $items[$product->id]['amount'] += $product->pivot->amount;

                if (!in_array($plan->day_of_week, $items[$product->id]['days'])) {
                    $items[$product->id]['days'][] = $plan->day_of_week;
                }
            }
        }

        usort($items, fn ($a, $b) => strcmp($a['name'], $b['name']));

        return view('plan.shopping', compact('items'));
    }
}
