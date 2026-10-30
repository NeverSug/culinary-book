<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use AuthorizesRequests;
    // Список продуктов пользователя (для формы рецепта и каталога)

    public function index()
    {
        $products = Product::orderBy('name')->paginate(10);

        return view('products.index', compact('products'));
    }

    // Форма создания
    public function create()
    {
        return view('products.create');
    }

    // Сохранение нового продукта
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255|unique:products,name',
            'calories'      => 'required|numeric|min:0',
            'proteins'      => 'nullable|numeric|min:0',
            'fats'          => 'nullable|numeric|min:0',
            'carbohydrates' => 'nullable|numeric|min:0',
            'piece_weight'  => 'nullable|required_if:unit,piece|numeric|min:0',
            'unit'          => 'required|in:g,ml,piece',
        ]);

        Auth::user()->products()->create($data);

        return redirect()->route('products.index');
    }

    // Форма редактирования
    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        return view('products.edit', compact('product'));
    }

    // Обновление
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validate([
            'name'          => 'required|string|max:255|unique:products,name,' . $product->id,
            'calories'      => 'required|numeric|min:0',
            'proteins'      => 'nullable|numeric|min:0',
            'fats'          => 'nullable|numeric|min:0',
            'carbohydrates' => 'nullable|numeric|min:0',
            'piece_weight'  => 'nullable|required_if:unit,piece|numeric|min:0',
            'unit'          => 'required|in:g,ml,piece',
        ]);

        $product->update($data);

        foreach ($product->recipes as $recipe) {
            $recipe->recalculateNutrition();
        }

        return redirect()->route('products.index');
    }

    // Удаление
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->recipes()->exists()) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Продукт используется в рецептах (' . $product->recipes()->count() . ') и не может быть удалён.');
        }

        $product->delete();

        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}
