<footer class="site-footer mt-5">
    <div class="container py-4">
        <div class="row gy-4">

            <div class="col-md-4">
                <h5 class="footer-title">Кулинарная книга</h5>
                <p class="footer-text mb-0">
                    Каталог рецептов, избранное и планирование меню на неделю —
                    всё, чтобы готовить без лишних забот.
                </p>
            </div>

            <div class="col-md-4">
                <h5 class="footer-title">Разделы</h5>
                <ul class="list-unstyled footer-links mb-0">
                    <li><a href="{{ route('recipes.index') }}">Рецепты</a></li>
                    <li><a href="{{ route('products.index') }}">Продукты</a></li>
                    @auth
                        <li><a href="{{ route('plan.index') }}">Расписание</a></li>
                        <li><a href="{{ route('plan.shopping') }}">Список покупок</a></li>
                        <li><a href="{{ route('favorites.index') }}">Избранное</a></li>
                    @endauth
                </ul>
            </div>

            <div class="col-md-4">
                <h5 class="footer-title">Контакты</h5>
                <ul class="list-unstyled footer-text mb-0">
                    <li>Почта: <a href="mailto:admin@example.com" class="footer-link">admin@example.com</a></li>
                    <li>Реклама: <a href="mailto:ads@example.com" class="footer-link">ads@example.com</a></li>
                </ul>
            </div>
            <div class="d-flex gap-3 small">
                <a href="{{ route('privacy') }}">Политика конфиденциальности</a>
                <a href="{{ route('consent') }}">Согласие на обработку данных</a>
            </div>

        </div>
    </div>

    <div class="footer-bottom py-3">
        <div class="container">
            <p class="mb-1">&copy; {{ date('Y') }} Мой сайт. Все права защищены.</p>
            <p class="mb-0">
                Все права на материалы, находящиеся на сайте, охраняются в соответствии с действующим
                законодательством. При любом использовании материалов сайта гиперссылка на сайт обязательна.<br>
                Администрация сайта не несет ответственности за результат применения приведенных кулинарных
                рецептов, методик их приготовления и кулинарных рекомендаций, работоспособность ресурсов,
                на которые размещены гиперссылки, и за содержание рекламных объявлений. Администрация сайта
                может не разделять мнения авторов статей, размещённых на сайте.
            </p>
        </div>
    </div>
</footer>

