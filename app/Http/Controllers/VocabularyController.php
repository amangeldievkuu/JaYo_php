<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use App\Services\DeepSeekService;
use Illuminate\Http\Request;

class VocabularyController extends Controller
{
    protected $deepSeek;

    public function __construct(DeepSeekService $deepSeek)
    {
        $this->deepSeek = $deepSeek;
    }

    public function generate(Post $post)
    {
        // Security: Only the owner can generate vocabularies
        if (auth()->id() !== $post->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Clear old vocabularies
        $post->vocabularies()->delete();

        // Get words list
        $content_f = $post->content_front;
        $words = $this->extractWords($content_f);

        $vocabList = [];

        foreach ($words as $word) {
            // Ask DeepSeek for Pinyin + Meaning
            $info = $this->deepSeek->translateSingleWord($word);

            if (!$info) continue;

            $vocab = Vocabulary::create([
                'post_id' => $post->id,
                'word' => $word,
                'pinyin' => $info['pinyin'],
                'translation' => $info['translation'],
            ]);

            $vocabList[] = $vocab;
        }

        return response()->json(['message' => 'Vocabulary generated', 'vocabularies' => $vocabList]);
    }

    private function extractWords($text)
    {
        // Basic simple extraction of unique Chinese characters
        preg_match_all("/\p{Han}+/u", $text, $matches);
        $words = collect($matches[0])->unique()->values()->all();
        return array_slice($words, 0, 20); // Limit to 20 words per post
    }
}
