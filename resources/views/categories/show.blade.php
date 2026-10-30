@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <div class="container">
        <h1 class="mb-1">{{ $category->name }}</h1>
        @if ($category->description)
            <p class="text-muted mb-4">{{ $category->description }}</p>
        @endif

        @if ($recipes->isEmpty())
            <div class="alert alert-info">
                В категории «{{ $category->name }}» пока нет рецептов.
                <a href="{{ route('recipes.create') }}">Создать первый</a>
            </div>
        @endif

        <div class="row">
            @foreach ($recipes as $recipe)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="{{ $recipe->cover_url }}" alt="{{ $recipe->name }}"
                             class="card-img-top" style="height: 180px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $recipe->name }}</h5>
                            <p class="card-text">{{ \Illuminate\Support\Str::limit($recipe->description, 80) }}</p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-sm btn-primary">Открыть</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
                {{ $recipes->links() }}

        <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary">← Все рецепты</a>
    </div>
@endsection
