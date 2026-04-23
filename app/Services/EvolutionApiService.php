<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EvolutionApiService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = get_setting('evolution_api_url');
        $this->apiKey = get_setting('evolution_api_key');
    }

    /**
     * Send a text message via WhatsApp.
     */
    public function sendMessage($instance, $number, $message)
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->post("{$this->baseUrl}/message/sendText/{$instance}", [
                'number' => $number,
                'text' => $message,
                'delay' => 1200,
            ]);

            return $response->successful() ? $response->json() : false;
        } catch (\Exception $e) {
            Log::error('Evolution API Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a media message (PDF/Image) via WhatsApp.
     */
    public function sendMedia($instance, $number, $mediaUrl, $caption = '', $fileName = 'curriculo.pdf')
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->post("{$this->baseUrl}/message/sendMedia/{$instance}", [
                'number' => $number,
                'mediaMessage' => [
                    'mediatype' => 'document',
                    'caption' => $caption,
                    'media' => $mediaUrl,
                    'fileName' => $fileName,
                ],
            ]);

            return $response->successful() ? $response->json() : false;
        } catch (\Exception $e) {
            Log::error('Evolution API Media Error: ' . $e->getMessage());
            return false;
        }
    }
}
