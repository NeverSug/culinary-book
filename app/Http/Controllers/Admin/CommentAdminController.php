<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentAdminController extends Controller
{
    /**
     * Список всех комментариев — свежие сверху.
     */
    public function index(Request $request)
    {
        $comments = Comment::with(['user', 'recipe'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = mb_strtolower($request->input('q'));
                $query->where(function ($c) use ($search) {
                    $c->whereRaw('LOWER(body) LIKE ?', ["%{$search}%"])
                        ->orWhereHas('user', fn ($u) => $u->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]));
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Удаление любого комментария модератором.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Комментарий удалён.');
    }
}
