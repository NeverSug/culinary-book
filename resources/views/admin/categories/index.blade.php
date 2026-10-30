@php use Illuminate\Support\Str; @endphp
@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Категории</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-dark">Добавить</a>
    </div>

    <div class="card border-0 shadow-sm">
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Рецептов</th>
                <th style="width: 120px;"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td class="text-muted small">
                        {{ Str::limit($category->description, 60) ?: '—' }}
                    </td>
                    <td>{{ $category->recipes_count }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="btn btn-sm btn-outline-dark">✎</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                              class="d-inline" onsubmit="return confirm('Удалить категорию?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">&times;</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="card-footer bg-white">{{ $categories->links() }}</div>
    </div>
@endsection
