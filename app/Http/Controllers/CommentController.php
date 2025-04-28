<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'comment_body' => 'required|string|max:1000',
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'comment_body' => $request->comment_body,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Comment created successfully!');

    }
}
