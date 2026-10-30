<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Кулинарная книга</a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#main-menu"
                aria-controls="main-menu" aria-expanded="false"
                aria-label="Переключить навигацию">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="main-menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('recipes.*') ? 'active' : '' }}"
                       href="{{ route('recipes.index') }}">Рецепты</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                       href="{{ route('products.index') }}">Продукты</a>
                </li>
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('recipes.create') ? 'active' : '' }}"
                           href="{{ route('recipes.create') }}">+ Новый рецепт</a>
                    </li>
                    <li class="nav-item">
                            @if (auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="nav-link">Админка</a>
                            @endif
                    </li>
                @endauth
            </ul>
            <form class="d-flex mx-lg-3 my-2 my-lg-0" method="GET" action="{{ route('recipes.index') }}" role="search">
                <div class="input-group input-group-sm">
                    <input type="search" name="q" value="{{ request('q') }}"
                           class="form-control" placeholder="Поиск рецептов…"
                           aria-label="Поиск рецептов">
                    <button class="btn btn-outline-light" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                        </svg>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('favorites.index') ? 'active' : '' }}"
                           href="{{ route('favorites.index') }}">Избранное</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('plan.index') ? 'active' : '' }}"
                           href="{{ route('plan.index') }}">Расписание</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('plan.shopping') ? 'active' : '' }}"
                           href="{{ route('plan.shopping') }}">Список покупок</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}"
                           href="{{ route('profile.show') }}">{{ Auth::user()->name }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                            Выйти
                        </a>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endauth
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Войти</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-lg-2" href="{{ route('register') }}">Регистрация</a>
                    </li>
                @endguest
            </ul>


        </div>
    </div>
</nav>
