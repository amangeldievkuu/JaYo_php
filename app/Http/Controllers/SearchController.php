<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke()
    {
        $q = request()->input('q');

        $posts = Post::with(['tags'])
            ->where('content_front', 'LIKE', '%' . $q . '%')
            ->orWhereHas('tags', function ($query) use ($q) {
                $query->where('name', 'LIKE', '%' . $q . '%');
            })->get();
        return view('results', ['posts' => $posts]);
    }
}
