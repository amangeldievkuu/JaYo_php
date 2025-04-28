<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Jobs\GenerateBackside;
use App\Models\Post;
use App\Models\Tag;
use App\Services\DeepSeekService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PostController extends Controller
{

    protected $deepSeek;

    public function __construct(DeepSeekService $deepSeek)
    {
        $this->deepSeek = $deepSeek;
    }

    /**
     * Display a listing of the resource.
     */
//    public function index()
//    {
//        $posts = Post::query()
//            ->latest()
//            ->with(['employer', 'tags'])
//            ->get()
//            ->groupBy('featured');
//        return view('posts.index', [
//            'featuredPosts' => $posts[1],
//            'posts' => $posts[0],
//            'tags' => Tag::all(),
//        ]);
//    }

    public function index()
    {
        $posts = Post::with(['user', 'likes']) // load user info for name/avatar
        ->where(function ($query) {
            $query->where('is_public', true);
            if (auth()->check()) {
                $query->orWhere('user_id', auth()->id()); // also see own private posts
            }
        })
            ->latest()
            ->paginate(10); // 10 posts per page

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
//    public function store(Request $request)
//    {
//        $attributes = $request->validate([
//            'title' => ['required'],
//            'salary' => ['required'],
//            'location' => ['required'],
//            'schedule' => ['required', Rule::in(['Full Time', 'Part Time', 'Internship'])],
//            'url' => ['required', 'active_url'],
//            'tags' => ['nullable'],
//        ]);
//
//        $attributes['featured'] = $request->has('featured');
//        $job = Auth::user()->employer->posts()->create(Arr::except($attributes, 'tags'));
//
//        if ($attributes['tags'] ?? false) {
//            foreach (explode(',', $attributes['tags']) as $tag) {
//                $job->tag($tag);
//            }
//        }
//
//        return redirect('/');
//    }

    public function store(Request $request)
    {
        $request->validate([
            'content_front' => 'required|string|max:2000',
            'tags' => 'nullable|string|max:255',
            'is_public' => 'nullable',
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'content_front' => $request->content_front,
            'is_public' => $request->has('is_public'),
        ]);

        if ($request->tags) {
            $tags = explode(',', $request->tags);
            foreach ($tags as $tagName) {
                $tagName = trim($tagName);
                if (!$tagName) continue;
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $post->tags()->attach($tag->id);
            }
        }

        GenerateBackside::dispatch($post->id);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user']); // eager load user info

        // Only allow viewing if public OR owner
        if (!$post->is_public && auth()->id() !== $post->user_id) {
            abort(403);
        }

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
