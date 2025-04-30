<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TagController;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
//    $mostLiked = Post::with(['user', 'likes'])
//        ->where(function ($query) {
//            $query->where('is_public', true);
//            if (auth()->check()) {
//                $query->orWhere('user_id', auth()->id());
//            }
//        })
//        ->latest()
//        ->take(3)   // ⚡ Only take 3 posts
//        ->get();

    $mostLiked = Post::with(['user', 'likes'])
        ->withCount('likes') // count likes as `likes_count`
        ->where(function ($query) {
            $query->where('is_public', true);
            if (auth()->check()) {
                $query->orWhere('user_id', auth()->id());
            }
        })
        ->orderByDesc('likes_count') // sort by likes count
        ->take(3)
        ->get();


    $posts = Post::with(['user', 'likes'])
        ->where(function ($query) {
            $query->where('is_public', true);
            if (auth()->check()) {
                $query->orWhere('user_id', auth()->id());
            }
        })->latest()->get();

    return view('welcome', ['posts' => $posts, 'mostLiked' => $mostLiked, 'tags' => Tag::all()]);
});

Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class);
    Route::get('/flashcards', [PostController::class, 'flashcards'])
        ->name('posts.flashcards');
});

Route::post('/posts/{post}/toggle-privacy', [PostController::class, 'togglePrivacy'])
    ->name('posts.togglePrivacy')
    ->middleware('auth');

Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])
    ->name('posts.like')
    ->middleware('auth');


Route::middleware(['auth'])
    ->post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->name('comments.store');

Route::get('/search', SearchController::class);
Route::get('/tags/{tag:name}', TagController::class);

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [SessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [SessionController::class, 'store']);
});

Route::delete('/logout', [SessionController::class, 'destroy'])
    ->middleware('auth');
