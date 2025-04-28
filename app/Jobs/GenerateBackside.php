<?php

namespace App\Jobs;

use App\Models\Post;
use App\Services\DeepSeekService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateBackside implements ShouldQueue
{
    use Queueable;
    protected $postId;
    /**
     * Create a new job instance.
     */
    public function __construct($postId)
    {
        $this->postId = $postId;
    }

    /**
     * Execute the job.
     */
    public function handle(DeepSeekService $deepSeek): void
    {
        $post = Post::find($this->postId);

        if (!$post) {
            return;
        }

        $backside = $deepSeek->translateAndPinyin($post->content_front);

        if ($backside) {
            $post->update([
                'content_back' => $backside,
            ]);
        }
    }
}
