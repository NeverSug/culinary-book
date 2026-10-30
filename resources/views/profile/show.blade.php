@extends('layouts.app')
@section('title', 'Личный кабинет — Кулинарная книга')
@section('content')
    <div class="container">
        <h1 class="mb-4">Личный кабинет</h1>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ $user->name }}</h5>
                <p class="text-muted mb-0">{{ $user->email }}</p>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm mt-3">
                    Редактировать профиль
                </a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">Избранное</h5>
                    <p class="text-muted mb-0">
                        Добавлено в Избранное: {{ $user->likes()->count() }}
                    </p>
                </div>

                <a href="{{ route('favorites.index') }}" class="btn btn-outline-primary btn-sm">
                   Открыть Избранное
                </a>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1">Расписание на неделю</h5>
                    <p class="text-muted mb-0">
                        Рецепты по дням недели: {{ $plans->flatten()->count() }} запланировано
                    </p>
                </div>
                <a href="{{ route('plan.index') }}" class="btn btn-outline-primary">Открыть план</a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-3">{{ $stats['recipes'] }}</div>
                        <div class="text-muted">моих рецептов</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-3">{{ $stats['likesGiven'] }}</div>
                        <div class="text-muted">лайков поставлено</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="fs-3">{{ $stats['likesReceived'] }}</div>
                        <div class="text-muted">лайков получено</div>
                    </div>
                </div>
            </div>
        </div>

        <h5>Мои рецепты</h5>

        <div class="row">
            @forelse ($myRecipes as $recipe)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        @if ($recipe->cover_path)
                            <img src="{{ $recipe->cover_url }}" class="card-img-top" alt="{{ $recipe->name }}">
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">{{ $recipe->name }}</h6>
                            <span class="badge bg-secondary">{{ $recipe->category?->name ?? 'Без категории' }}</span>
                            <span class="text-muted">♥ {{ $recipe->likes_count }}</span>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-sm btn-outline-primary">Открыть</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">Пока нет рецептов.
                    <a href="{{ route('recipes.create') }}">Создайте первый!</a></p>
            @endforelse
        </div>

        {{ $myRecipes->links() }}
    </div>

@endsection
