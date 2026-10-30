<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\Product;
use App\Models\Comment;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'recipesCount'  => Recipe::count(),
            'productsCount' => Product::count(),
            'commentsCount' => Comment::count(),
            'usersCount'    => User::count(),
            'latestComments' => Comment::with('user', 'recipe')
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }

}
