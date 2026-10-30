@extends('layouts.app')

@section('title', 'Ошибка сервера')

@section('content')
    <div class="error-page">
        <div class="error-page__icon">🥴</div>
        <h1 class="error-page__code">500</h1>
        <p class="error-page__title">Что-то пошло не так</p>
        <p class="error-page__text">Мы уже разбираемся. Попробуйте обновить страницу или вернуться позже.</p>
        <div class="error-page__actions">
            <a href="{{ url('/') }}" class="error-page__btn error-page__btn--primary">На главную</a>
        </div>
    </div>
@endsection
