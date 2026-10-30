@extends('layouts.app')

@section('title', 'Доступ запрещён')

@section('content')
    <style>
        .error-page {
            min-height: 60vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
        }
        .error-page__icon { font-size: 72px; line-height: 1; margin-bottom: 16px; }
        .error-page__code {
            font-size: 96px;
            font-weight: 800;
            margin: 0;
            letter-spacing: 4px;
            color: #e76f51;
        }
        .error-page__title { font-size: 24px; font-weight: 600; margin: 8px 0; }
        .error-page__text { color: #6b7280; max-width: 480px; margin: 0 0 24px; }
        .error-page__actions { display: flex; gap: 12px; flex-wrap: wrap; justify-content: center; }
        .error-page__btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: opacity .2s;
        }
        .error-page__btn:hover { opacity: .85; }
        .error-page__btn--primary { background: #e76f51; color: #fff; }
        .error-page__btn--secondary { background: #f3f4f6; color: #374151; }
    </style>

    <div class="error-page">
        <div class="error-page__icon">🔒</div>

        <h1 class="error-page__code">403</h1>
        <p class="error-page__title">Доступ запрещён</p>
        <p class="error-page__text">
            У вас нет прав на это действие. Возможно, этот рецепт принадлежит
            другому автору или действие доступно только администратору.
        </p>

        <div class="error-page__actions">
            <a href="{{ url()->previous() }}" class="error-page__btn error-page__btn--primary">
                Назад
            </a>
            <a href="{{ route('recipes.index') }}" class="error-page__btn error-page__btn--secondary">
                К рецептам
            </a>
        </div>
    </div>
@endsection
