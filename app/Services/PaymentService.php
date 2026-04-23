<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Payment;
use App\Models\Plan;

class PaymentService
{
    protected $token;
    protected $baseUrl = 'https://api.abacatepay.com/v1';

    public function __construct()
    {
        $this->token = get_setting('abacatepay_api_key') ?: env('ABACATEPAY_API_KEY');
    }

    /**
     * Garantir que o usuário tenha um Customer ID no AbacatePay
     */
    public function getOrCreateCustomer(User $user)
    {
        if ($user->abacate_customer_id) {
            return $user->abacate_customer_id;
        }

        try {
            $response = Http::withToken($this->token)
                ->post("{$this->baseUrl}/customer/create", [
                    'name' => $user->name,
                    'email' => $user->email,
                    'taxId' => '00000000000', // CPF genérico ou coletado no perfil
                ]);

            if ($response->successful()) {
                $customerId = $response->json()['data']['id'];
                $user->update(['abacate_customer_id' => $customerId]);
                return $customerId;
            }

            Log::error('Erro ao criar cliente AbacatePay: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Exceção AbacatePay Customer: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Criar sessão de Checkout (Billing)
     */
    public function criarPagamentoParaUtilizador(User $user, $planId = null)
    {
        $customerId = $this->getOrCreateCustomer($user);
        if (!$customerId) return null;

        $plan = $planId ? Plan::find($planId) : null;
        $priceInCents = $plan ? ($plan->price * 100) : 4990; // R$ 49,90 padrão
        $name = $plan ? $plan->name : 'Plano Profissional JobBot AI';

        try {
            $response = Http::withToken($this->token)
                ->post("{$this->baseUrl}/billing/create", [
                    'frequency' => 'ONE_TIME',
                    'methods' => ['PIX', 'CARD'],
                    'products' => [
                        [
                            'externalId' => $plan ? $plan->id : 'standard_plan',
                            'name' => $name,
                            'quantity' => 1,
                            'price' => $priceInCents,
                        ]
                    ],
                    'returnUrl' => route('dashboard'),
                    'completionUrl' => route('dashboard'),
                    'customerId' => $customerId,
                ]);

            if ($response->successful()) {
                $data = $response->json()['data'];
                
                // Registrar o pagamento pendente no banco local
                Payment::create([
                    'user_id' => $user->id,
                    'amount' => $priceInCents / 100,
                    'status' => 'pending',
                    'external_id' => $data['id'],
                    'payment_method' => 'abacatepay'
                ]);

                return $data['url'];
            }

            Log::error('Erro ao criar faturamento AbacatePay: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Exceção AbacatePay Billing: ' . $e->getMessage());
            return null;
        }
    }
}
