@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Комментарии</h1>
        <form method="GET" class="d-flex gap-2">
            <input type="search" name="q" value="{{ request('q') }}"
                   class="form-control form-control-sm" placeholder="Поиск по автору или тексту…">
            <button class="btn btn-sm btn-outline-dark">Найти</button>
        </form>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="list-group list-group-flush">
            @forelse ($comments as $comment)
                <div class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <strong>{{ $comment->user->name }}</strong>
                        <span class="text-muted small">в «{{ $comment->recipe->name }}»</span><br>
                        <small class="text-muted">
                            {{ $comment->created_at->translatedFormat('d F Y в H:i') }}
                        </small>
                        <div class="mt-1">{{ $comment->body }}</div>
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
        <div class="card-footer bg-white">{{ $comments->links() }}</div>
    </div>
@endsection
