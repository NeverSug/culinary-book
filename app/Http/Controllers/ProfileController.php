<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $plans = $user->mealPlans()
            ->with('recipe')
            ->get()
            ->groupBy('day_of_week');

        $likedRecipes = $user->likes()
            ->orderBy('name')
            ->get();

        $stats = [
            'recipes'      => $user->recipes()->count(),
            'likesGiven'   => $user->likes()->count(),
            'likesReceived' => $user->recipes()->withCount('likes')->get()->sum('likes_count'),
        ];

        $myRecipes = $user->recipes()
            ->with(['category'])
            ->withCount('likes')
            ->latest()
            ->paginate(6);

        return view('profile.show', compact('user', 'plans', 'likedRecipes', 'stats', 'myRecipes'));
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($data);

        return redirect()
            ->route('profile.show')
            ->with('status', 'Профиль обновлён.');
    }
    public function favorites()
    {
        $user = Auth::user();

        $likedRecipes = $user->likes()
            ->with('category')
            ->orderBy('name')
            ->get();

        return view('profile.favorites', compact('likedRecipes'));
    }
}
