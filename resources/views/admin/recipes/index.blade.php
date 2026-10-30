@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Рецепты</h1>
        <a href="{{ route('admin.recipes.create') }}" class="btn btn-dark">Добавить</a>
    </div>

    <div class="card border-0 shadow-sm">
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <th>Название</th>
                <th>Категория</th>
                <th>Комментарии</th>
                <th style="width: 160px;"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($recipes as $recipe)
                <tr>
                    <td>{{ $recipe->name }}</td>
                    <td>{{ $recipe->category->name ?? '—' }}</td>
                    <td>{{ $recipe->comments()->count() }}</td>
                    <td class="text-end">
                        <a href="{{ route('recipes.show', $recipe) }}"
                           class="btn btn-sm btn-outline-secondary" title="Посмотреть на сайте">👁</a>
                        <a href="{{ route('admin.recipes.edit', $recipe) }}"
                           class="btn btn-sm btn-outline-dark">✎</a>
                        <form method="POST" action="{{ route('admin.recipes.destroy', $recipe) }}"
                              class="d-inline" onsubmit="return confirm('Удалить рецепт?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">&times;</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="card-footer bg-white">{{ $recipes->links() }}</div>
    </div>
@endsection
