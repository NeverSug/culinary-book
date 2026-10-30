@extends('layouts.app')
@section('title', 'Избранное — Кулинарная книга')
@section('content')
    <div class="container">
        <h1 class="mb-4">Избранные рецепты</h1>
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <div id="fav-flash" class="alert alert-success py-2 d-none"></div>
        <a href="{{ route('plan.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
            Распланировать неделю →
        </a>

        <div class="row">
            @forelse ($likedRecipes as $recipe)
                <div class="col-md-4 mb-3 fav-card" data-recipe-id="{{ $recipe->id }}">
                    <div class="card h-100">
                        @if ($recipe->cover_path)
                            <img src="{{ $recipe->cover_url }}" class="card-img-top" alt="{{ $recipe->name }}">
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">{{ $recipe->name }}</h6>
                            <a href="{{ route('categories.show', $recipe->category) }}"
                               class="badge text-bg-secondary text-decoration-none mb-2">
                                {{ $recipe->category->name }}
                            </a>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('recipes.show', $recipe) }}" class="btn btn-sm btn-outline-primary">Открыть</a>

                            <button type="button"
                                    class="btn btn-sm btn-outline-danger unlike-btn"
                                    data-recipe-id="{{ $recipe->id }}">&times; убрать</button>
                        </div>
                            <form action="{{ route('plan.store') }}" method="POST"
                                  class="d-flex gap-2 plan-add-form">
                                @csrf
                                <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                                <select name="day_of_week" class="form-select form-select-sm" required>
                                    <option value="" selected>день…</option>
                                    @foreach (\App\Models\MealPlan::DAYS as $dayNum => $dayName)
                                        <option value="{{ $dayNum }}">{{ $dayName }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-primary text-nowrap">В план</button>
                            </form>
                    </div>
                </div>
            @empty
                <p class="text-muted">
                    Пока пусто. Отмечайте рецепты сердечком в
                    <a href="{{ route('recipes.index') }}">каталоге</a> — они появятся здесь.
                </p>
            @endforelse
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content
                ?? '{{ csrf_token() }}';
            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
            };

            function showFlash(text) {
                const el = document.getElementById('fav-flash');
                el.textContent = text;
                el.classList.remove('d-none');
                clearTimeout(showFlash._t);
                showFlash._t = setTimeout(() => el.classList.add('d-none'), 2500);
            }

            document.addEventListener('click', async (e) => {
                const btn = e.target.closest('.unlike-btn');
                if (!btn) return;

                const card = btn.closest('.fav-card');
                const response = await fetch(`{{ url('recipes') }}/${btn.dataset.recipeId}/like`, {
                    method: 'POST',
                    headers: headers,
                });

                if (!response.ok) {
                    alert('Не удалось убрать лайк');
                    return;
                }

                card.remove();
                showFlash('Рецепт убран из избранного.');

                if (!document.querySelector('.fav-card')) {
                    const p = document.createElement('p');
                    p.className = 'text-muted';
                    p.innerHTML = 'Пока пусто. Отмечайте рецепты сердечком в <a href="{{ route('recipes.index') }}">каталоге</a>.';
                    document.querySelector('.row').append(p);
                }
            });
            document.addEventListener('submit', async (e) => {
                const form = e.target.closest('.plan-add-form');
                if (!form) return;
                e.preventDefault();

                const daySelect = form.querySelector('select[name="day_of_week"]');
                if (!daySelect.value) {
                    showFlash('Выберите день недели');
                    return;
                }

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: headers,
                    body: new FormData(form),
                });

                if (!response.ok) {
                    alert('Не удалось добавить в план');
                    return;
                }

                const data = await response.json();
                showFlash(data.message);
                form.reset();
            });
        });
    </script>
@endsection
