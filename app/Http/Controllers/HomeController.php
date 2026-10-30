<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home', [
            'latestRecipes' => Recipe::with('products')
                ->latest()
                ->take(6)
                ->get(),

            'categories' => Category::withCount('recipes')
                ->orderByDesc('recipes_count')
                ->take(8)
                ->get(),

            'recipesCount'  => Recipe::count(),
            'productsCount' => Product::count(),
        ]);
    }
}
