<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';
    protected $model = 'gemini-1.5-flash';

    public function __construct()
    {
        $this->apiKey = get_setting('gemini_api_key') ?: env('GEMINI_API_KEY');
    }

    public function generateContent($prompt)
    {
        try {
            $response = Http::timeout(30)->post("{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                return $response->json()['candidates'][0]['content']['parts'][0]['text'];
            }

            Log::error('Gemini Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return null;
        }
    }
}
