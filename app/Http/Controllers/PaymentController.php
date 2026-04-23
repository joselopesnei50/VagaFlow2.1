<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;

class PaymentController extends Controller
{
    protected $paymentService;
    protected $notifier;

    public function __construct()
    {
        $this->paymentService = new PaymentService();
        $this->notifier = new \App\Services\NotificationService();
    }

    /**
     * Iniciar Checkout
     */
    public function checkout(Request $request)
    {
        $user = auth()->user();
        
        $checkoutUrl = $this->paymentService->criarPagamentoParaUtilizador($user, $request->plan_id);

        if ($checkoutUrl) {
            return redirect($checkoutUrl);
        }

        return redirect()->back()->with('error', 'Houve um erro ao iniciar o pagamento. Por favor, tente novamente.');
    }

    /**
     * Webhook do AbacatePay
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();
        Log::info('AbacatePay Webhook Recebido:', $payload);

        // Estrutura esperada: data.id e data.status
        $externalId = $payload['data']['id'] ?? null;
        $status = $payload['data']['status'] ?? null;

        if ($externalId && $status === 'paid') {
            $payment = Payment::where('external_id', $externalId)->first();

            if ($payment && $payment->status !== 'paid') {
                $payment->update(['status' => 'paid']);
                
                // Ativar benefícios para o usuário
                $user = $payment->user;
                $user->update([
                    'is_premium' => true,
                    'credits' => 30, // Exatamente 30 envios concedidos
                ]);

                // Notificar o Usuário
                $this->notifier->notifyPaymentConfirmed($user, $payment->amount);

                Log::info("Pagamento confirmado e conta Premium ativada para: {$user->email}");
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
