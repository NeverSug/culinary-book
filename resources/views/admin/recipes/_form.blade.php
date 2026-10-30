@csrf

<div class="mb-3">
    <label class="form-label" for="name">Название</label>
    <input type="text" name="name" id="name" value="{{ old('name', $recipe->name ?? '') }}"
           class="form-control @error('name') is-invalid @enderror" required>
    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="description">Описание</label>
    <textarea name="description" id="description" rows="4"
              class="form-control @error('description') is-invalid @enderror">{{ old('description', $recipe->description ?? '') }}</textarea>
    @error('description')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="category_id">Категория</label>
    <select name="category_id" id="category_id"
            class="form-select @error('category_id') is-invalid @enderror">
        <option value="">— без категории —</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                @selected((string) old('category_id', $recipe->category_id ?? '') === (string) $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label d-block">Продукты</label>
    <div class="row g-2">
        @foreach ($products as $product)
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="products[]"
                           value="{{ $product->id }}" id="prod-{{ $product->id }}"
                        @checked(in_array($product->id, old('products', $recipe->products->pluck('id')->all() ?? [])))>
                    <label class="form-check-label" for="prod-{{ $product->id }}">{{ $product->name }}</label>
                </div>
            </div>
        @endforeach
    </div>
</div>

<button type="submit" class="btn btn-dark">{{ isset($recipe) ? 'Сохранить' : 'Создать' }}</button>
