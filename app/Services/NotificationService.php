<?php

namespace App\Services;

use App\Models\User;
use App\Models\Application;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $evolution;
    protected $brevo;

    public function __construct()
    {
        $this->evolution = new EvolutionApiService();
        $this->brevo = new BrevoService();
    }

    /**
     * Notify user via WhatsApp that a new application was sent
     */
    public function notifyApplicationSent(Application $application)
    {
        $user = $application->user;
        $whatsapp = $user->profile->whatsapp_number ?? null;

        if (!$whatsapp) return;

        $message = "🚀 *JobBot AI: Candidatura Enviada!*\n\n";
        $message .= "Acabamos de enviar sua apresentação para a empresa *{$application->company_name}*.\n";
        $message .= "📊 *Match Score:* {$application->match_score}%\n";
        $message .= "💡 *Estratégia:* {$application->strategy_note}\n\n";
        $message .= "Acompanhe tudo no seu painel: " . route('dashboard');

        // Note: We use a global admin instance to notify the user, 
        // OR the user's own instance if it's connected.
        $instance = 'admin_inst'; // Fallback to system instance
        $this->evolution->sendMessage($instance, $whatsapp, $message);
    }

    /**
     * Notify user about payment confirmation
     */
    public function notifyPaymentConfirmed(User $user, $amount)
    {
        // 1. WhatsApp
        if ($user->profile && $user->profile->whatsapp_number) {
            $message = "✅ *Pagamento Confirmado!*\n\n";
            $message .= "Olá {$user->name}, seu pagamento de R$ " . number_format($amount, 2, ',', '.') . " foi aprovado.\n";
            $message .= "Sua conta agora é *PREMIUM* e adicionamos *100 créditos* de bônus para você!\n\n";
            $message .= "Boas candidaturas!";
            
            $this->evolution->sendMessage('admin_inst', $user->profile->whatsapp_number, $message);
        }

        // 2. Email
        $this->brevo->sendEmail(
            $user->email,
            $user->name,
            "Pagamento Confirmado - JobBot AI Premium",
            "<h1>Parabéns, {$user->name}!</h1><p>Seu pagamento foi confirmado e sua conta Premium está ativa. Você recebeu 100 créditos adicionais.</p>"
        );
    }

    /**
     * Notify user when a job hunt starts (Autopilot)
     */
    public function notifyAutopilotStarted(User $user)
    {
        if ($user->profile && $user->profile->whatsapp_number) {
            $message = "🤖 *Piloto Automático Ativado!*\n\n";
            $message .= "Sua IA começou a varrer a internet em busca de vagas para *{$user->profile->target_role}*.\n";
            $message .= "Você receberá notificações aqui conforme as candidaturas forem enviadas.";
            
            $this->evolution->sendMessage('admin_inst', $user->profile->whatsapp_number, $message);
        }
    }
}
