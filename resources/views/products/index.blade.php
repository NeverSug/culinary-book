@extends('layouts.app')
@section('title', 'Каталог продуктов — Кулинарная книга')

@section('content')
    <div class="container">
        @if (session('error'))
            <div class="alert alert-warning">{{ session('error') }}</div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Продукты</h1>
            @auth()
                <a href="{{ route('products.create') }}" class="btn btn-primary">+ Добавить продукт</a>
            @endauth
        </div>
        @if ($products->isEmpty())
            <div class="alert alert-info">
                Каталог пуст. Войдите, чтобы добавить первый продукт — начните с базовых: крупы, овощи, молоко.
            </div>
        @else
            <table class="table table-striped align-middle">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Ккал (на 100 г)</th>
                    <th>Белки</th>
                    <th>Жиры</th>
                    <th>Углеводы</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->calories }}</td>
                        <td>{{ $product->proteins }} г</td>
                        <td>{{ $product->fats }} г</td>
                        <td>{{ $product->carbohydrates }} г</td>
                        <td class="text-end">

                            @can('update', $product)
                                <a href="{{ route('products.edit', $product) }}"
                                   class="btn btn-sm btn-outline-secondary">Изменить</a>
                            @endcan
                            @can('delete', $product)
                                <form action="{{ route('products.destroy', $product) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Удалить продукт?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Удалить</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
                {{ $products->links() }}
        @endif
    </div>
@endsection
