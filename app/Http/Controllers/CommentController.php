<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Recipe $recipe)
    {
        $request->validate([
            'body' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        $comment = $recipe->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $request->input('body'),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Комментарий добавлен.',
                'html'    => view('recipes.partials.comment', ['comment' => $comment])->render(),
            ], 201);
        }

        return back()->with('success', 'Комментарий добавлен.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment)
    {
        abort_if($comment->user_id !== $request->user()->id, 403,
            'Можно удалять только свои комментарии.');

        $comment->delete();

        return back()->with('success', 'Комментарий удалён.');
    }
}
