<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepSeekService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.deepseek.com';

    public function __construct()
    {
        $this->apiKey = get_setting('deepseek_api_key');
    }

    public function chat($messages)
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model' => 'deepseek-chat',
                    'messages' => $messages,
                    'temperature' => 0.7,
                ]);

            if ($response->successful()) {
                return $response->json()['choices'][0]['message']['content'];
            }

            Log::error('DeepSeek Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('DeepSeek Exception: ' . $e->getMessage());
            return null;
        }
    }
}
