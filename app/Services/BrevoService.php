<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.brevo.com/v3';

    public function __construct()
    {
        $this->apiKey = get_setting('brevo_api_key');
    }

    /**
     * Send a transactional email.
     */
    public function sendEmail($toEmail, $toName, $subject, $contentHtml)
    {
        try {
            $response = Http::withHeaders([
                'api-key' => $this->apiKey,
                'content-type' => 'application/json',
                'accept' => 'application/json',
            ])->post("{$this->baseUrl}/smtp/email", [
                'sender' => [
                    'name' => config('app.name'),
                    'email' => 'contato@' . request()->getHost(),
                ],
                'to' => [
                    ['email' => $toEmail, 'name' => $toName]
                ],
                'subject' => $subject,
                'htmlContent' => $contentHtml,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Brevo API Error: ' . $e->getMessage());
            return false;
        }
    }
}
