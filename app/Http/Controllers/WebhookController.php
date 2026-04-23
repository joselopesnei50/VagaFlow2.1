<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function whatsapp(Request $request)
    {
        $data = $request->all();
        \Illuminate\Support\Facades\Log::info('WhatsApp Webhook Received', $data);

        // A Evolution API costuma enviar o status em data['status'] ou data['type']
        // Verificamos se há um ID de mensagem para atualizar
        $msgId = $data['data']['key']['id'] ?? $data['data']['id'] ?? null;
        $status = $data['data']['status'] ?? null;

        if ($msgId && $status) {
            $mappedStatus = match($status) {
                'RECEIVED' => 'delivered',
                'DELIVERY' => 'delivered',
                'READ' => 'read',
                default => 'sent'
            };

            \App\Models\Application::where('message_id', $msgId)->update([
                'delivery_status' => $mappedStatus
            ]);
        }

        return response()->json(['status' => 'success']);
    }
}
