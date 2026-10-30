@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Продукты</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-dark">Добавить продукт</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                <tr>
                    <th>Название</th>
                    <th class="text-nowrap">КБЖУ на 100 г</th>
                    <th class="text-end">В рецептах</th>
                    <th style="width: 120px;" class="text-end"></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <strong>{{ $product->name }}</strong>
                        </td>
                        <td class="text-muted small">
                            {{ $product->calories }} ккал / Б {{ $product->proteins }} / Ж {{ $product->fats }} /
                            У {{ $product->carbohydrates }}
                        </td>
                        <td class="text-end text-muted small">{{ $product->recipes_count }}</td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="btn btn-sm btn-outline-dark"
                                   title="Редактировать">✎</a>
                                <form method="POST"
                                      action="{{ route('admin.products.destroy', $product) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Удалить продукт? Нельзя, если он используется в рецептах.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Удалить">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">Продуктов пока нет.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="card-footer bg-white">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
