@csrf

<div class="mb-3">
    <label for="name" class="form-label">Название продукта</label>
    <input type="text"
           id="name"
           name="name"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $product->name ?? '') }}"
           required
           autofocus>
    @error('name')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<fieldset>
    <legend class="fs-6 text-muted mb-3">Пищевая ценность на 100 г</legend>

    <div class="row g-3">
        <div class="col-6 col-md-3">
            <label for="calories" class="form-label">Калории, ккал</label>
            <input type="number" step="0.1" min="0" max="10000"
                   id="calories" name="calories"
                   class="form-control @error('calories') is-invalid @enderror"
                   value="{{ old('calories', $product->calories ?? 0) }}">
            @error('calories')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-6 col-md-3">
            <label for="proteins" class="form-label">Белки, г</label>
            <input type="number" step="0.1" min="0" max="100"
                   id="proteins" name="proteins"
                   class="form-control @error('proteins') is-invalid @enderror"
                   value="{{ old('proteins', $product->proteins ?? 0) }}">
            @error('proteins')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-6 col-md-3">
            <label for="fats" class="form-label">Жиры, г</label>
            <input type="number" step="0.1" min="0" max="100"
                   id="fats" name="fats"
                   class="form-control @error('fats') is-invalid @enderror"
                   value="{{ old('fats', $product->fats ?? 0) }}">
            @error('fats')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-6 col-md-3">
            <label for="carbs" class="form-label">Углеводы, г</label>
            <input type="number" step="0.1" min="0" max="100"
                   id="carbs" name="carbs"
                   class="form-control @error('carbs') is-invalid @enderror"
                   value="{{ old('carbs', $product->carbohydrates ?? 0) }}">
            @error('carbs')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</fieldset>

<button type="submit" class="btn btn-dark mt-3">
    {{ isset($product) ? 'Сохранить изменения' : 'Создать продукт' }}
</button>
