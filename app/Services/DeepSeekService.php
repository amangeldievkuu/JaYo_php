<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DeepSeekService
{
    protected $apiUrl = 'https://openrouter.ai/api/v1/chat/completions';

    public function translateAndPinyin($text)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openrouter.key'), // ⚡ Read OpenRouter API key
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl, [
                'model' => 'meta-llama/llama-4-maverick:free', // ⚡ Correct model name
                'messages' => [
                    ['role' => 'system', 'content' =>
                    'You are a Mandarin Chinese language assistant.
For the following Chinese text, please output structured JSON like this:
{
  "pinyin": "...",
  "translation": "...",
  "breakdown": [
    {"word": "我", "pinyin": "wǒ", "meaning": "I"},
    {"word": "是", "pinyin": "shì", "meaning": "am"},
    {"word": "學生/学生", "pinyin": "xué shēng", "meaning": "student"}
  ],
}
Focus on clear, natural English meanings. Always include breakdown. No explanation. No text outside the JSON.
'],
                    ['role' => 'user', 'content' => $text],
                ],
            ]);

            if ($response->failed()) {
                \Log::error('OpenRouter DeepSeek API Error: ' . $response->body());
                return null;
            }

            $data = $response->json();
            $content =  $data['choices'][0]['message']['content'] ?? null;

            return json_decode($content, true);

        } catch (\Exception $e) {
            \Log::error('OpenRouter DeepSeek Exception: ' . $e->getMessage());
            return null;
        }
    }
}
