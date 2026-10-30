<div class="border-bottom py-3" data-comment-id="{{ $comment->id }}">
    <div class="d-flex justify-content-between align-items-center">
        <strong>{{ $comment->user->name }}</strong>
        <div class="d-flex align-items-center gap-2">
            <small class="text-muted">{{ $comment->created_at->translatedFormat('d F Y в H:i') }}</small>

            @if (auth()->id() === $comment->user_id)
                <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                      class="js-delete-comment d-inline" onsubmit="return false;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Удалить">&times;</button>
                </form>
            @endif
        </div>
    </div>
    <p class="mb-0 mt-1">{{ $comment->body }}</p>
</div>
