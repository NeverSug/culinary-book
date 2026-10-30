@extends('layouts.app')
@section('title', 'Расписание — Кулинарная книга')

@section('content')
    <div class="container">
        <h1 class="mb-4">Расписание на неделю</h1>

        <div id="plan-flash" class="alert alert-success py-2 d-none"></div>

        <a href="{{ route('plan.shopping') }}" class="btn btn-outline-primary">
            Список покупок
        </a>

        <h5 class="mt-4">Расписание на неделю</h5>

        <div class="row g-3 mb-4">
            @foreach (\App\Models\MealPlan::DAYS as $dayNum => $dayName)
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header fw-semibold">{{ $dayName }}</div>
                        <div class="card-body" data-day="{{ $dayNum }}">
                            <div class="plan-items">
                                @forelse (($plans[$dayNum] ?? collect()) as $plan)
                                    <div class="d-flex justify-content-between align-items-center mb-2 plan-item"
                                         data-plan-id="{{ $plan->id }}">
                                        <a href="{{ route('recipes.show', $plan->recipe) }}">
                                            {{ $plan->recipe->name }}
                                        </a>
                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm plan-remove">&times;</button>
                                    </div>
                                @empty
                                    <span class="text-muted plan-empty">— ничего не запланировано —</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if ($likedRecipes->isEmpty())
            <p class="text-muted">
                Сначала выберите понравившиеся рецепты в <a href="{{ route('recipes.index') }}">каталоге</a> —
                они появятся здесь для планирования.
            </p>
        @else
            <form id="plan-form" action="{{ route('plan.store') }}" method="POST" class="row g-2 align-items-center">
                @csrf
                <div class="col-md-6">
                    <select name="recipe_id" class="form-select" required>
                        <option value="">— выберите рецепт —</option>
                        @foreach ($likedRecipes as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="day_of_week" class="form-select" required>
                        <option value="">— день —</option>
                        @foreach (\App\Models\MealPlan::DAYS as $dayNum => $dayName)
                            <option value="{{ $dayNum }}">{{ $dayName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Добавить</button>
                </div>
            </form>
        @endif

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('plan-form');
            if (!form) return;

            const csrf = form.querySelector('input[name="_token"]').value;
            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
            };

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: headers,
                    body: new FormData(form),
                });

                if (!response.ok) {
                    alert('Не удалось добавить рецепт');
                    return;
                }

                const data = await response.json();
                const cell = document.querySelector(`[data-day="${data.day}"] .plan-items`);

                // рецепт уже стоит на этом дне — не дублируем
                if (cell.querySelector(`[data-plan-id="${data.planId}"]`)) {
                    alert('Этот рецепт уже добавлен на этот день');
                    form.reset();
                    return;
                }

                const empty = cell.querySelector('.plan-empty');
                if (empty) empty.remove();

                const item = document.createElement('div');
                item.className = 'd-flex justify-content-between align-items-center mb-2 plan-item';
                item.dataset.planId = data.planId;

                const link = document.createElement('a');
                link.href = `/recipes/${data.recipeId}`;
                link.textContent = data.recipe;

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-outline-danger btn-sm plan-remove';
                btn.innerHTML = '&times;';

                item.append(link, btn);
                cell.append(item);

                form.reset();
            });

            document.addEventListener('click', async (e) => {
                const btn = e.target.closest('.plan-remove');
                if (!btn) return;

                const item = btn.closest('.plan-item');
                const cell = btn.closest('.plan-items');   // ← сначала ищем контейнер

                const response = await fetch(`/plan/${item.dataset.planId}`, {
                    method: 'DELETE',
                    headers: headers,
                });

                if (!response.ok) {
                    alert('Не удалось убрать рецепт');
                    return;
                }

                item.remove();

                if (!cell.querySelector('.plan-item')) {
                    const empty = document.createElement('span');
                    empty.className = 'text-muted plan-empty';
                    empty.textContent = '— ничего не запланировано —';
                    cell.append(empty);
                }
            });
        });
    </script>
@endsection
