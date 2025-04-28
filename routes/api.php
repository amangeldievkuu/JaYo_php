<?php

use App\Http\Controllers\VocabularyController;

Route::middleware(['auth:sanctum'])
    ->post('/posts/{post}/vocabulary', [VocabularyController::class, 'generate'])
    ->name('posts.vocabulary');
