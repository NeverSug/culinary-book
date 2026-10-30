@extends('admin.layouts.app')

@section('content')
    <h1 class="h3 mb-4">Обзор</h1>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="h3 mb-0">{{ $recipesCount }}</div>
                    <div class="text-muted small">рецептов</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="h3 mb-0">{{ $productsCount }}</div>
                    <div class="text-muted small">продуктов</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="h3 mb-0">{{ $commentsCount }}</div>
                    <div class="text-muted small">комментариев</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="h3 mb-0">{{ $usersCount }}</div>
                    <div class="text-muted small">пользователей</div>
                </div>
            </div>
        </div>
    </div>

    <h2 class="h5 mb-3">Последние комментарии</h2>
    <div class="card border-0 shadow-sm">
        <div class="list-group list-group-flush">
            @forelse ($latestComments as $comment)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $comment->user->name }}</strong>
                        в «{{ $comment->recipe->name }}»:<br>
                        <small class="text-muted">{{ Str::limit($comment->body, 80) }}</small>
                    </div>
                    <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}"
                          onsubmit="return confirm('Удалить комментарий?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">&times;</button>
                    </form>
                </div>
            @empty
                <div class="list-group-item text-muted">Комментариев нет.</div>
            @endforelse
        </div>
    </div>
@endsection
