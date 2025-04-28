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
                'model' => 'deepseek/deepseek-r1:free', // ⚡ Correct model name
                'messages' => [
                    ['role' => 'system', 'content' => 'Translate the following Chinese into English and provide pinyin transcription. Format nicely.'],
                    ['role' => 'user', 'content' => $text],
                ],
            ]);

            if ($response->failed()) {
                \Log::error('OpenRouter DeepSeek API Error: ' . $response->body());
                return null;
            }

            $data = $response->json();
            return $data['choices'][0]['message']['content'] ?? null;

        } catch (\Exception $e) {
            \Log::error('OpenRouter DeepSeek Exception: ' . $e->getMessage());
            return null;
        }
    }
}
