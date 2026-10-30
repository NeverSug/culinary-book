<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Админка — {{ $title ?? 'Кулинарная книга' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Админка</span>
        <div>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-light">На сайт</a>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 col-lg-2 px-0 bg-white border-end min-vh-100">
            <nav class="nav flex-column p-3">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">Статистика</a>
                <a class="nav-link" href="{{ route('admin.recipes.index') }}">Рецепты</a>
                <a class="nav-link" href="{{ route('admin.products.index') }}">Продукты</a>
                <a class="nav-link" href="{{ route('admin.categories.index') }}">Категории</a>
                <a class="nav-link" href="{{ route('admin.comments.index') }}">Комментарии</a>
            </nav>
        </aside>

        <main class="col-md-9 col-lg-10 p-4">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
