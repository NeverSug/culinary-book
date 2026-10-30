@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Редактировать рецепт</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('recipes.update', $recipe) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Название</label>
                <input type="text" name="name" value="{{ old('name', $recipe->name) }}"
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Категория</label>
                <select name="category_id" id="category_id"
                        class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">— без категории —</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            @selected(old('category_id', $recipe->category_id ?? null) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Описание</label>
                <textarea name="description" rows="3"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $recipe->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="cover" class="form-label">Обложка</label>
                <input type="file" name="cover" id="cover"
                       class="form-control @error('cover') is-invalid @enderror"
                       accept="image/jpeg,image/png,image/webp">
                @error('cover')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @if ($recipe->cover_path)
                    <img src="{{ $recipe->cover_url }}" alt="Обложка" class="img-fluid rounded mt-2" style="max-height: 200px;">
                @endif
            </div>

            <h5 class="mt-4">Ингредиенты</h5>
            @error('products')
            <div class="text-danger mb-2">{{ $message }}</div>
            @enderror

            <div id="items-container">
                @foreach ($oldProducts as $i => $item)
                    <div class="row g-2 mb-2 item-row align-items-center">
                        <div class="col-md-6">
                            <select name="products[{{ $i }}][id]" class="form-select product-select">
                                <option value="">— выберите продукт —</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-unit="{{ $product->unit }}"
                                        {{ (string) old('products.'.$i.'.id', $item['id']) == (string) $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" step="0.01" min="0.01" name="products[{{ $i }}][amount]"
                                   value="{{ old('products.'.$i.'.amount', $item['amount']) }}"
                                   class="form-control" placeholder="Кол-во" required>
                        </div>
                        <div class="col-md-2">
                            <span class="unit-label fw-semibold">{{ $units[$item['id']] ?? '—' }}</span>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-item" title="Удалить">&times;</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-item">+ Добавить ингредиент</button>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-outline-secondary">Отмена</a>
            </div>
        </form>

        <template id="item-template">
            <div class="row g-2 mb-2 item-row align-items-center">
                <div class="col-md-6">
                    <select name="products[__i__][id]" class="form-select product-select">
                        <option value="">— выберите продукт —</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-unit="{{ $product->unit }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.01" min="0.01" name="products[__i__][amount]" class="form-control" placeholder="Кол-во">
                </div>
                <div class="col-md-2">
                    <span class="unit-label fw-semibold">—</span>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-item" title="Удалить">&times;</button>
                </div>
            </div>
        </template>
    </div>
@endsection

@push('scripts')
    <script>
        let i = {{ count($oldProducts) }};

        const UNIT_LABELS = { g: 'г', ml: 'мл', piece: 'шт' };

        document.getElementById('add-item').addEventListener('click', () => {
            const tpl = document.getElementById('item-template').innerHTML
                .replaceAll('__i__', i++);
            document.getElementById('items-container')
                .insertAdjacentHTML('beforeend', tpl);
        });

        document.addEventListener('change', (e) => {
            const select = e.target.closest('.product-select');
            if (!select) return;

            const unit = select.selectedOptions[0]?.dataset.unit ?? '';
            select.closest('.item-row').querySelector('.unit-label').textContent =
                UNIT_LABELS[unit] ?? '—';
        });

        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.remove-item');
            if (!btn) return;
            btn.closest('.item-row').remove();
            reindex();
        });

        function reindex() {
            document.querySelectorAll('#items-container .item-row').forEach((row, index) => {
                row.querySelectorAll('[name^="products["]').forEach((field) => {
                    field.name = field.name.replace(/products\[\d+\]/, `products[${index}]`);
                });
            });
            i = document.querySelectorAll('#items-container .item-row').length;
        }
    </script>
@endpush
