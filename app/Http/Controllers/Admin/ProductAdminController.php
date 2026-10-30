<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductAdminController extends Controller
{
    /**
     * Список продуктов.
     */
    public function index()
    {
        $products = Product::withCount('recipes')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Форма создания.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Сохранение нового продукта.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'calories' => ['nullable', 'numeric', 'min:0', 'max:10000'],
            'proteins' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'fats'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'carbohydrates'    => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Продукт создан.');
    }

    /**
     * В админке не нужна — редирект на редактирование.
     */
    public function show(Product $product)
    {
        return redirect()->route('admin.products.edit', $product);
    }

    /**
     * Форма редактирования.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Обновление продукта.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name,' . $product->id],
            'calories' => ['nullable', 'numeric', 'min:0', 'max:10000'],
            'proteins' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'fats'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'carbohydrates'    => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Продукт обновлён.');
    }

    /**
     * Удаление с защитой от удаления используемого продукта.
     */
    public function destroy(Product $product)
    {
        if ($product->recipes()->exists()) {
            return back()->with('error',
                'Сначала уберите продукт из рецептов, где он используется.');
        }

        $product->delete();

        return back()->with('success', 'Продукт удалён.');
    }
}
