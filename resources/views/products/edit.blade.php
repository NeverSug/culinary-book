@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 640px;">
        <h1 class="mb-4">Редактирование: {{ $product->name }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Название</label>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $product->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <h5 class="mt-4">Пищевая ценность <span class="text-muted small">(на 100 г)</span></h5>
            <div class="mb-3">
                <label for="calories" class="form-label">Калории, ккал</label>
                <input type="number" name="calories" id="calories"
                       class="form-control @error('calories') is-invalid @enderror"
                       value="{{ old('calories', $product->calories) }}" min="0" step="0.1" required>
                @error('calories') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="unit" class="form-label">Единица измерения</label>
                <select name="unit" id="unit"
                        class="form-select @error('unit') is-invalid @enderror">
                    <option value="g" @selected(old('unit', $product->unit) === 'g')>граммы (г)</option>
                    <option value="ml" @selected(old('unit', $product->unit) === 'ml')>миллилитры (мл)</option>
                    <option value="piece" @selected(old('unit', $product->unit) === 'piece')>штуки (шт)</option>
                </select>
                @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3" id="piece_weight_block">
                <label for="piece_weight" class="form-label">Вес одной штуки, г</label>
                <input type="number" name="piece_weight" id="piece_weight"
                       class="form-control @error('piece_weight') is-invalid @enderror"
                       value="{{ old('piece_weight', $product->piece_weight) }}" min="0" step="0.1">
                @error('piece_weight') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="proteins" class="form-label">Белки, г</label>
                    <input type="number" name="proteins" id="proteins"
                           class="form-control"
                           value="{{ old('proteins', $product->proteins) }}" min="0" step="0.1">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fats" class="form-label">Жиры, г</label>
                    <input type="number" name="fats" id="fats"
                           class="form-control"
                           value="{{ old('fats', $product->fats) }}" min="0" step="0.1">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="carbohydrates" class="form-label">Углеводы, г</label>
                    <input type="number" name="carbohydrates" id="carbohydrates"
                           class="form-control"
                           value="{{ old('carbohydrates', $product->carbohydrates) }}" min="0" step="0.1">
                </div>
            </div>





            <button type="submit" class="btn btn-primary">Сохранить</button>
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
