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

        $result = $deepSeek->translateAndPinyin($post->content_front);

        if ($result) {
            $post->update([
                'word' => json_encode($result['breakdown'] ?? []),
                'pinyin' => $result['pinyin'] ?? null,
                'translation' => $result['translation'] ?? null,
            ]);
        }
    }
}
