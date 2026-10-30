<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Recipe;

class RecipeAdminController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with('products')->latest()->paginate(15);

        return view('admin.recipes.index', compact('recipes'));
    }

    public function create()
    {
        return view('admin.recipes.create', [
            'products' => Product::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'category_id'   => ['nullable', 'exists:categories,id'],
            'products'      => ['array'],
            'products.*'    => ['exists:products,id'],
        ]);

        $recipe = Recipe::create($data);
        $recipe->products()->sync($request->input('products', []));

        return redirect()->route('admin.recipes.index')
            ->with('success', 'Рецепт создан.');
    }

    public function edit(Recipe $recipe)
    {
        return view('admin.recipes.edit', [
            'recipe' => $recipe,
            'products' => Product::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Recipe $recipe)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'category_id'   => ['nullable', 'exists:categories,id'],
            'products'      => ['array'],
            'products.*'    => ['exists:products,id'],
        ]);

        $recipe->update($data);
        $recipe->products()->sync($request->input('products', []));

        return redirect()->route('admin.recipes.index')
            ->with('success', 'Рецепт обновлён.');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return back()->with('success', 'Рецепт удалён.');
    }
}
