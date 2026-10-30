<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RecipeController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $recipes = Recipe::with('products')
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->input('q');

                $query->where(function ($inner) use ($q) {
                    $inner->where('name', 'LIKE', "%{$q}%")
                        ->orWhere('description', 'LIKE', "%{$q}%")
                        ->orWhereHas('products', fn ($p) => $p->where('products.name', 'LIKE', "%{$q}%"));
                });
            })
            ->when($request->filled('product_id'), function ($query) use ($request) {
                $query->whereHas('products', fn ($p) => $p->where('products.id', $request->input('product_id')));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $products = Product::orderBy('name')->pluck('name', 'id');

        return view('recipes.index', compact('recipes', 'products'));
    }

    public function create()
    {
        $products    = Product::orderBy('name')->get();
        $categories  = Category::orderBy('name')->get();
        $oldProducts = old('products', []);

        return view('recipes.create', compact('products', 'categories', 'oldProducts'));
    }

    public function store(Request $request)
    {
        $this->prepareProductsInput($request);

        $data = $request->validate($this->rules());

        $coverPath = $request->hasFile('cover')
            ? $request->file('cover')->store('covers', 'public')
            : null;

        $recipe = Auth::user()->recipes()->create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'cover_path'  => $coverPath,
            'category_id' => $data['category_id'] ?? null,
        ]);

        $this->syncProducts($recipe, $data['products']);

        return redirect()->route('recipes.show', $recipe);
    }

    public function show(Recipe $recipe)
    {
        $recipe->load('products');

        return view('recipes.show', compact('recipe'));
    }

    public function edit(Recipe $recipe)
    {
        $this->authorize('update', $recipe);

        $products   = Product::orderBy('name')->get();
        $units      = $products->pluck('unit_label', 'id')->all();
        $categories = Category::orderBy('name')->get();

        $oldProducts = old('products', $recipe->products->map(fn ($p) => [
            'id'     => $p->id,
            'amount' => $p->pivot->amount,
        ])->all());

        return view('recipes.edit', compact('recipe', 'products', 'categories', 'oldProducts', 'units'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        $this->authorize('update', $recipe);

        $this->prepareProductsInput($request);

        $data = $request->validate($this->rules());

        $updateData = [
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'category_id' => $data['category_id'] ?? null,
        ];

        if ($request->hasFile('cover')) {
            if ($recipe->cover_path) {
                Storage::disk('public')->delete($recipe->cover_path);
            }
            $updateData['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        $recipe->update($updateData);

        $this->syncProducts($recipe, $data['products']);

        return redirect()->route('recipes.show', $recipe);
    }

    public function destroy(Recipe $recipe)
    {
        $this->authorize('delete', $recipe);

        if ($recipe->cover_path) {
            Storage::disk('public')->delete($recipe->cover_path);
        }

        $recipe->likes()->detach();
        $recipe->products()->detach();
        $recipe->delete();

        return redirect()
            ->route('recipes.index')
            ->with('status', 'Рецепт удалён.');
    }

    public function like(Recipe $recipe)
    {
        $recipe->likes()->toggle(Auth::id());

        if (request()->wantsJson()) {
            return response()->json([
                'liked' => $recipe->likes()->where('user_id', Auth::id())->exists(),
                'count' => $recipe->likes()->count(),
            ]);
        }

        return back()->with('status', $recipe->likes()->where('user_id', Auth::id())->exists()
            ? 'Рецепт добавлен в избранное.'
            : 'Рецепт убран из избранного.');
    }

    private function prepareProductsInput(Request $request): Request
    {
        $request->merge([
            'products' => collect($request->products)
                ->filter(fn ($item) => filled($item['id'] ?? null) || filled($item['amount'] ?? null))
                ->values()
                ->all(),
        ]);

        return $request;
    }

    private function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string|max:10000',
            'products'          => 'required|array|min:1',
            'products.*.id'     => 'required|integer|exists:products,id',
            'products.*.amount' => 'required|numeric|min:0.1',
            'cover'             => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            'category_id'       => 'nullable|exists:categories,id',
        ];
    }

    private function syncProducts(Recipe $recipe, array $products): void
    {
        $amounts = collect($products)->pluck('amount', 'id');

        $pivotData = Product::findMany($amounts->keys())
            ->mapWithKeys(fn ($p) => [
                $p->id => [
                    'amount' => $amounts[$p->id],
                    'unit'   => $p->unit,
                ],
            ])->all();

        $recipe->products()->sync($pivotData);
        $recipe->recalculateNutrition();
    }
}
