@extends('layouts.app')

@section('content')

    {{-- HERO --}}
    <section class="hero d-flex align-items-center text-white">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <h1 class="display-4 fw-bold mb-3">Готовьте вкусно.<br>Планируйте просто.</h1>
                    <p class="lead text-white-50 mb-4">
                        Кулинарная книга с планировщиком меню на неделю: выбирайте рецепты,
                        сохраняйте любимое и получайте готовый список покупок.
                    </p>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('recipes.index') }}" class="btn btn-warning btn-lg px-4">
                            Смотреть рецепты
                        </a>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-4">
                                Начать бесплатно
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ЦИФРЫ --}}
    <section class="stats py-4 bg-white border-bottom">
        <div class="container">
            <div class="row text-center g-3">
                <div class="col-4">
                    <div class="h3 fw-bold mb-0">{{ $recipesCount }}</div>
                    <div class="text-muted small">рецептов</div>
                </div>
                <div class="col-4">
                    <div class="h3 fw-bold mb-0">{{ $productsCount }}</div>
                    <div class="text-muted small">продуктов</div>
                </div>
                <div class="col-4">
                    <div class="h3 fw-bold mb-0">7</div>
                    <div class="text-muted small">дней планирования</div>
                </div>
            </div>
        </div>
    </section>

    {{-- КАТЕГОРИИ --}}
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <h2 class="h3 mb-0">Популярные категории</h2>
            </div>
            <div class="row g-3">
                @foreach ($categories as $category)
                    <div class="col-6 col-md-3">
                        <a href="{{ route('categories.show', $category) }}"
                           class="d-block text-decoration-none category-card p-4 rounded-3 h-100">
                            <div class="fw-semibold">{{ $category->name }}</div>
                            <div class="text-muted small mt-1">
                                Рецептов: {{ $category->recipes_count }}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ПОСЛЕДНИЕ РЕЦЕПТЫ --}}
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="h3 mb-4">Свежие рецепты</h2>
            <div class="row g-4">
                @foreach ($latestRecipes as $recipe)
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="{{ $recipe->cover_url ?? 'https://via.placeholder.com/600x400' }}"
                                 class="card-img-top" alt="{{ $recipe->name }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $recipe->name }}</h5>
                                <p class="card-text text-muted small mb-0">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($recipe->description), 80) }}
                                </p>
                            </div>
                            <div class="card-footer bg-white border-0 pb-3">
                                <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-sm btn-outline-dark">
                                    Готовить
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('recipes.index') }}" class="btn btn-dark px-4">Все рецепты</a>
            </div>
        </div>
    </section>

    {{-- ПРИЗЫВ --}}
    <section class="cta py-5 text-white text-center">
        <div class="container py-3">
            <h2 class="mb-3">Уже добираете продукты на неделю?</h2>
            <p class="text-white-50 mb-4">Составьте план на 7 дней — список покупок соберётся сам.</p>
            @auth
                <a href="{{ route('plan.index') }}" class="btn btn-warning btn-lg px-4">Мой план питания</a>
            @endauth
            @guest
                <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-4">Зарегистрироваться</a>
            @endguest
        </div>
    </section>

@endsection
