<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AbacatePayService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.abacatepay.com/v1';

    public function __construct()
    {
        $this->apiKey = get_setting('abacatepay_api_key');
    }

    /**
     * Create a new checkout session.
     */
    public function createCheckout($amount, $user)
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->post("{$this->baseUrl}/checkout/create", [
                    'amount' => $amount * 100, // In cents
                    'customer' => [
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'return_url' => route('dashboard'),
                    'webhook_url' => route('webhooks.abacatepay'),
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AbacatePay Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AbacatePay Exception: ' . $e->getMessage());
            return null;
        }
    }
}
