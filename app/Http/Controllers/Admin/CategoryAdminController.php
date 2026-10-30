<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Http\Request;

class CategoryAdminController extends Controller
{
    /**
     * Список категорий.
     */
    public function index()
    {
        $categories = Category::withCount('recipes')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Форма создания.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Сохранение новой категории.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория создана.');
    }

    public function show(Category $category)
    {
        return redirect()->route('admin.categories.edit', $category);
    }

    /**
     * Форма редактирования.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Обновление категории.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория обновлена.');
    }

    /**
     * Удаление с защитой от потери рецептов.
     */
    public function destroy(Category $category)
    {
        if ($category->recipes()->exists()) {
            return back()->with('error',
                'Сначала переместите или удалите рецепты этой категории.');
        }

        $category->delete();

        return back()->with('success', 'Категория удалена.');
    }
}
