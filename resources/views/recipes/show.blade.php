@extends('layouts.app')
@section('title', $recipe->name . ' — Кулинарная книга')

@section('content')
    <div class="container" style="max-width: 800px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">{{ $recipe->name }}</h1>
            @if ($recipe->category)
                <a href="{{ route('categories.show', $recipe->category) }}"
                   class="badge text-bg-secondary text-decoration-none mb-2">
                    {{ $recipe->category->name }}
                </a>
            @endif
            <div>
                @auth
                    <button type="button"
                            class="btn btn-sm like-btn {{ $recipe->likes->isNotEmpty() ? 'btn-danger' : 'btn-outline-danger' }}"
                            data-url="{{ route('recipes.like', $recipe) }}">
                        <span class="like-icon">{{ $recipe->likes->isNotEmpty() ? '♥' : '♡' }}</span>
                        <span class="like-count">{{ $recipe->likes_count }}</span>
                    </button>
                @endauth
                @can('update', $recipe)
                    <a href="{{ route('recipes.edit', $recipe) }}"
                       class="btn btn-outline-secondary">Изменить</a>
                @endcan
                @can('delete', $recipe)
                    <form action="{{ route('recipes.destroy', $recipe) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Удалить рецепт?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger">Удалить</button>
                    </form>
                @endcan
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Пищевая ценность</div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col">
                        <div class="text-muted small">всего на блюдо</div>
                        <div class="h4 mb-0">{{ $recipe->total_calories }} ккал</div>
                        <div class="text-muted small">Белки: {{ $recipe->total_proteins }} </div>
                        <div class="text-muted small">Жиры: {{ $recipe->total_fats }} </div>
                        <div class="text-muted small">Углеводы: {{ $recipe->total_carbohydrates }} </div>
                    </div>
                    <div class="col">
                        <div class="text-muted small">на 100 г</div>
                        <div class="h4 mb-0">{{ $recipe->calories_per_100g }} ккал</div>
                        <div class="text-muted small">Белки: {{ $recipe->proteins_per_100g }} </div>
                        <div class="text-muted small">Жиры: {{ $recipe->fats_per_100g }} </div>
                        <div class="text-muted small">Углеводы: {{ $recipe->carbohydrates_per_100g }} </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-muted small">
                Общий вес: {{ $recipe->total_weight }} г ·
                Автор: {{ $recipe->user->name }}
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Ингредиенты</span>

            </div>
            <div class="list-group list-group-flush">
                @forelse ($recipe->products as $product)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $product->name }}</strong>
                            <div class="text-muted small">
                                {{ $product->calories }} ккал/100 г
                            </div>
                        </div>
                        <div class="text-end">
                            <div>{{ $product->pivot->amount }} {{ $product->unit_label }}</div>
                            <div class="text-muted small">
                                {{ $recipe->calculateProductCalories($product) }} ккал
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-muted">Ингредиенты не добавлены</div>
                @endforelse
            </div>
        </div>
        <img src="{{ $recipe->cover_url }}" alt="{{ $recipe->name }}" class="img-fluid rounded mb-3">
        @if ($recipe->description)
            <div class="card mb-4">
                <div class="card-header">Приготовление</div>
                <div class="card-body" style="white-space: pre-line;">
                    {{ $recipe->description }}
                </div>
            </div>
        @endif
        {{-- Комментарии --}}
        <section class="mt-5">
            <h2 class="h4 mb-4">
                Комментарии <span class="text-muted small">(<span class="js-comments-count">{{ $recipe->comments->count() }}</span>)</span>
            </h2>


            @auth
                <form method="POST" action="{{ route('recipes.comments.store', $recipe) }}"
                      class="js-comment-form mb-4" onsubmit="return false;">
                    @csrf
                    <div class="mb-2">
            <textarea name="body" rows="3" id="commentBody"
                      class="form-control @error('body') is-invalid @enderror"
                      placeholder="Поделитесь мнением о рецепте…">{{ old('body') }}</textarea>
                        <div class="invalid-feedback js-comment-error"></div>
                    </div>
                    <button class="btn btn-dark js-comment-submit" type="submit">Отправить</button>
                </form>
            @endauth

            @guest
                <p class="text-muted mb-4">
                    <a href="{{ route('login') }}">Войдите</a>, чтобы оставить комментарий.
                </p>
            @endguest
            <div class="js-comments-list">
            @forelse ($recipe->comments as $comment)
                @include('recipes.partials.comment', ['comment' => $comment])
            @empty
                <p class="text-muted js-empty-comments">Комментариев пока нет. Будьте первым!</p>
            @endforelse
            </div>
        </section>

        <a href="{{ route('recipes.index') }}" class="btn btn-outline-secondary">← Ко всем рецептам</a>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('.js-comment-form');
            if (!form) return; // гость — формы нет

            const textarea = form.querySelector('textarea[name="body"]');
            const errorBox = form.querySelector('.js-comment-error');
            const submitBtn = form.querySelector('.js-comment-submit');
            const list = document.querySelector('.js-comments-list');
            const counter = document.querySelector('.js-comments-count');

            function bumpCounter(delta) {
                if (counter) {
                    const n = parseInt(counter.textContent) + delta;
                    counter.textContent = n;
                }
            }

            form.addEventListener('submit', async function () {
                errorBox.textContent = '';
                textarea.classList.remove('is-invalid');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Отправляю…';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        },
                        body: new FormData(form),
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        // Ошибки валидации: Laravel отдаёт 422 и { errors: {...} }
                        const messages = data.errors?.body ?? ['Что-то пошло не так'];
                        errorBox.textContent = messages[0];
                        textarea.classList.add('is-invalid');
                        return;
                    }

                    // Успех: вставляем отрендеренный сервером фрагмент
                    list.insertAdjacentHTML('beforeend', data.html);
                    list.querySelector('.js-empty-comments')?.remove();

                    textarea.value = '';
                    bumpCounter(1);
                } catch (e) {
                    errorBox.textContent = 'Ошибка сети. Попробуйте ещё раз.';
                    textarea.classList.add('is-invalid');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Отправить';
                }
            });

            // Удаление без перезагрузки — бонусом раз уж взялись
            list.addEventListener('click', async function (e) {
                const deleteForm = e.target.closest('.js-delete-comment');
                if (!deleteForm) return;
                if (!confirm('Удалить комментарий?')) return;

                const response = await fetch(deleteForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': deleteForm.querySelector('input[name="_token"]').value,
                        'X-HTTP-Method-Override': 'DELETE',
                        'Accept': 'application/json',
                    },
                });

                if (response.ok) {
                    deleteForm.closest('[data-comment-id]').remove();
                    bumpCounter(-1);
                }
            });
        });
    </script>

@endsection
