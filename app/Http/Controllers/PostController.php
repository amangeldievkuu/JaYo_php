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
    public function index()
    {
        $posts = Post::with(['user', 'likes']) // load user info for name/avatar
        ->where(function ($query) {
            $query->where('is_public', true);
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
        // Only allow editing by the owner
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        return view('posts.edit', compact('post'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Authorization: only post owner can update
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'content_front' => 'required|string|max:2000',
            'tags' => 'nullable|string|max:255',
            'is_public' => 'nullable|boolean',
        ]);

        // Update post
        $post->update([
            'content_front' => $validated['content_front'],
            'is_public' => $request->has('is_public'),
        ]);

        // Handle tags (replace all)
        if ($request->filled('tags')) {
            $tags = collect(explode(',', $request->tags))
                ->map(fn($tag) => trim($tag))
                ->filter()
                ->map(fn($name) => \App\Models\Tag::firstOrCreate(['name' => $name]))
                ->pluck('id');

            $post->tags()->sync($tags);
        } else {
            $post->tags()->detach(); // No tags provided
        }

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }

    public function flashcards()
    {
        $posts = Post::where('user_id', auth()->id())
            ->with(['user', 'tags', 'likes'])
            ->latest()
            ->paginate(10);

        return view('posts.flashcards', compact('posts'));
    }

    public function togglePrivacy(Post $post)
    {
        // Ensure user owns the post
        if (auth()->id() !== $post->user_id) {
            abort(403);
        }

        $post->is_public = !$post->is_public;
        $post->save();

        return back()->with('success', 'Post visibility updated!');
    }

}
