@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Новый рецепт</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('recipes.store') }}" method="POST" id="recipe-form" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Название рецепта</label>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Категория</label>
                <select name="category_id" id="category_id"
                        class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">— без категории —</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            @selected(old('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Описание (пошагово)</label>
                <textarea name="description" id="description" rows="5"
                          class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="cover" class="form-label">Обложка</label>
                <input type="file" name="cover" id="cover"
                       class="form-control @error('cover') is-invalid @enderror"
                       accept="image/jpeg,image/png,image/webp">
                @error('cover')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <h5 class="mt-4">Ингредиенты</h5>

            <div id="ingredient-rows">
                @foreach (old('products', []) as $i => $item)
                    <div class="row g-2 mb-2 ingredient-row align-items-center">
                        <div class="col-md-5">
                            <select name="products[{{ $i }}][id]" class="form-select product-select" required>
                                <option value="">— выберите продукт —</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-unit="{{ $product->unit }}"
                                        {{ (string) old('products.'.$i.'.id', $item['id'] ?? null) == (string) $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                        @if ($product->unit === 'piece')
                                            ({{ $product->piece_weight }} г/шт)
                                        @else
                                            ({{ $product->calories }} ккал/100 {{ $product->unit === 'ml' ? 'мл' : 'г' }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="products[{{ $i }}][amount]"
                                   value="{{ old('products.'.$i.'.amount', $item['amount'] ?? null) }}"
                                   class="form-control" placeholder="Кол-во" min="0.01" step="0.01" required>
                        </div>
                        <div class="col-md-2">
                            <span class="form-control unit-label">—</span>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-danger w-100 btn-remove">Удалить</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-outline-secondary mb-3" id="add-row">
                + Добавить ингредиент
            </button>

            <div>
                <button type="submit" class="btn btn-primary">Создать рецепт</button>
            </div>
        </form>
    </div>

    <template id="row-template">
        <div class="row g-2 mb-2 ingredient-row align-items-center">
            <div class="col-md-5">
                <select name="products[ROW][id]" class="form-select product-select" required>
                    <option value="">— выберите продукт —</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-unit="{{ $product->unit }}">
                            {{ $product->name }}
                            @if ($product->unit === 'piece')
                                ({{ $product->piece_weight }} г/шт)
                            @else
                                ({{ $product->calories }} ккал/100 {{ $product->unit === 'ml' ? 'мл' : 'г' }})
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="products[ROW][amount]"
                       class="form-control" placeholder="Кол-во" min="0.01" step="0.01" required>
            </div>
            <div class="col-md-2">
                <span class="form-control unit-label">—</span>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger w-100 btn-remove">Удалить</button>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('ingredient-rows');
            const template  = document.getElementById('row-template');
            const UNIT_LABELS = { g: 'г', ml: 'мл', piece: 'шт' };
            let rowIndex = {{ count(old('products', [])) }};

            function setRowLabel(row) {
                const select = row.querySelector('.product-select');
                const unit   = select.selectedOptions[0]?.dataset.unit;
                const label  = row.querySelector('.unit-label');
                if (label) {
                    label.textContent = UNIT_LABELS[unit] ?? '—';
                }
            }

            function addRow() {
                const html = template.innerHTML.replaceAll('ROW', rowIndex++);
                container.insertAdjacentHTML('beforeend', html);
            }
            container.querySelectorAll('.ingredient-row').forEach(setRowLabel);
            if (!container.querySelector('.ingredient-row')) {
                addRow();
            }

            document.getElementById('add-row').addEventListener('click', addRow);

            container.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-remove')) {
                    const rows = container.querySelectorAll('.ingredient-row');
                    if (rows.length > 1) {
                        e.target.closest('.ingredient-row').remove();
                    }
                }
            });

            container.addEventListener('change', function (e) {
                if (!e.target.classList.contains('product-select')) return;
                setRowLabel(e.target.closest('.ingredient-row'));
            });
        });
    </script>
@endsection
