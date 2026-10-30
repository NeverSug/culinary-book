@extends('layouts.app')
@section('title', 'Каталог рецептов — Кулинарная книга')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Рецепты</h1>
            @if ($recipes->isEmpty() && request('q'))
                <div class="alert alert-light border">
                    Ничего не нашлось. Попробуйте другое название или упомяните продукт, например «курица».
                </div>
            @endif
            <form method="GET" action="{{ route('recipes.index') }}" class="mb-4">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="q" value="{{ request('q') }}"
                               class="form-control" placeholder="Название или описание…">
                    </div>
                    <div class="col-md-4">
                        <select name="product_id" class="form-select">
                            <option value="">Любой продукт</option>
                            @foreach ($products as $id => $name)
                                <option value="{{ $id }}" @selected((string) request('product_id') === (string) $id)>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-dark w-100" type="submit">Найти</button>
                    </div>
                </div>
            </form>

            @if (request('q') !== null || request('product_id') !== null)
                <p class="text-muted">
                    Найдено: {{ $recipes->total() }}
                    <a href="{{ route('recipes.index') }}" class="ms-2">Сбросить</a>
                </p>
            @endif

        @auth
            <a href="{{ route('recipes.create') }}" class="btn btn-primary">+ Добавить рецепт</a>
        @endauth
        </div>
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if ($recipes->isEmpty())
            <div class="alert alert-info">
                Рецептов пока нет. Создайте первый!
            </div>
        @else
            <div class="row">
                <div class="mb-3">
                    <span class="text-muted me-2">Часто ищут:</span>
                    @foreach ($products->take(6) as $id => $name)
                        <a href="{{ route('recipes.index', ['product_id' => $id]) }}"
                           class="btn btn-sm btn-outline-dark me-1 mb-1 {{ (string) request('product_id') === (string) $id ? 'active' : '' }}">
                            {{ $name }}
                        </a>
                    @endforeach
                </div>
                @foreach ($recipes as $recipe)
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <img src="{{ $recipe->cover_url }}" alt="{{ $recipe->name }}" class="img-fluid rounded" style="height: 160px; object-fit: cover; width: 100%;">
                                @if ($recipe->category)
                                    <a href="{{ route('categories.show', $recipe->category) }}"
                                       class="badge text-bg-secondary text-decoration-none mb-2">
                                        {{ $recipe->category->name }}
                                    </a>
                                @endif
                                <h5 class="card-title">
                                    <a href="{{ route('recipes.show', $recipe) }}">{{ $recipe->name }}</a>
                                </h5>
                                <p class="card-text text-truncate">
                                    {{ $recipe->description ?? 'Без описания' }}
                                </p>
                                <ul class="list-unstyled small mb-2">
                                    <li>Автор: {{ $recipe->user->name }}</li>
                                    <li>Калорийность: {{ $recipe->total_calories }} ккал</li>
                                    <li>На 100 г: {{ $recipe->calories_per_100g }} ккал</li>
                                </ul>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <span class="text-muted">{{ $recipe->products->count() }} ингредиентов</span>

                                <div class="d-flex align-items-center gap-2">
                                    @auth
                                        <button type="button"
                                                class="btn btn-sm like-btn {{ $recipe->likes->isNotEmpty() ? 'btn-danger' : 'btn-outline-danger' }}"
                                                data-url="{{ route('recipes.like', $recipe) }}">
                                            <span class="like-icon">{{ $recipe->likes->isNotEmpty() ? '♥' : '♡' }}</span>
                                            <span class="like-count">{{ $recipe->likes_count }}</span>
                                        </button>
                                    @endauth
                                    @guest
                                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary" title="Войдите, чтобы лайкнуть">
                                            ♡ {{ $recipe->likes_count }}
                                        </a>
                                    @endguest

                                    @can('update', $recipe)
                                        <a href="{{ route('recipes.edit', $recipe) }}"
                                           class="btn btn-sm btn-outline-secondary">Изменить</a>
                                    @endcan
                                    @can('delete', $recipe)
                                        <form action="{{ route('recipes.destroy', $recipe) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Удалить рецепт?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Удалить</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $recipes->links() }}
        @endif
    </div>
@endsection
