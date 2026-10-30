@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 640px;">
        <h1 class="mb-4">Новый продукт</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Название</label>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <h5 class="mt-4">Пищевая ценность <span class="text-muted small">(на 100 г или 100 мл)</span></h5>

            <div class="mb-3">
                <label for="calories" class="form-label">Калории, ккал</label>
                <input type="number" name="calories" id="calories"
                       class="form-control @error('calories') is-invalid @enderror"
                       value="{{ old('calories', 0) }}" min="0" step="0.1" required>
                @error('calories') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="proteins" class="form-label">Белки, г</label>
                    <input type="number" name="proteins" id="proteins"
                           class="form-control"
                           value="{{ old('proteins', 0) }}" min="0" step="0.1">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fats" class="form-label">Жиры, г</label>
                    <input type="number" name="fats" id="fats"
                           class="form-control"
                           value="{{ old('fats', 0) }}" min="0" step="0.1">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="carbohydrates" class="form-label">Углеводы, г</label>
                    <input type="number" name="carbohydrates" id="carbohydrates"
                           class="form-control"
                           value="{{ old('carbohydrates', 0) }}" min="0" step="0.1">
                </div>
            </div>


            <div class="mb-3">
                <label for="unit" class="form-label">Единица измерения</label>
                <select name="unit" id="unit"
                        class="form-select @error('unit') is-invalid @enderror">
                    <option value="g" @selected(old('unit', 'g') === 'g')>граммы (г)</option>
                    <option value="ml" @selected(old('unit') === 'ml')>миллилитры (мл)</option>
                    <option value="piece" @selected(old('unit') === 'piece')>штуки (шт)</option>
                </select>
                @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="piece_weight" class="form-label">
                    Вес одной штуки, г
                    <span class="text-muted small">(только для штучных продуктов: яйцо, яблоко…)</span>
                </label>
                <input type="number" name="piece_weight" id="piece_weight"
                       class="form-control @error('piece_weight') is-invalid @enderror"
                       value="{{ old('piece_weight') }}" min="0" step="0.1"
                       placeholder="например, 55 для яйца">
                @error('piece_weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Добавить продукт</button>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Отмена</a>
        </form>
    </div>
    @push('scripts')
        <script>
            const unitSelect = document.getElementById('unit');
            const pieceBlock = document.getElementById('piece_weight_block');

            function togglePiece() {
                pieceBlock.style.display = unitSelect.value === 'piece' ? '' : 'none';
            }

            unitSelect.addEventListener('change', togglePiece);
            togglePiece();
        </script>
    @endpush
@endsection
