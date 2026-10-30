<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $recipes = $category->recipes()
            ->with('products')
            ->latest()
            ->paginate(6);

        return view('categories.show', compact('category', 'recipes'));
    }
}
