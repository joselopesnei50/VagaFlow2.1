<?php

namespace App\Services;

use App\Models\User;
use App\Models\Application;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $evolution;
    protected $brevo;

    // Nome da instância Evolution API do sistema (configurada no admin)
    const SYSTEM_INSTANCE = 'jobbot_system';

    public function __construct()
    {
        $this->evolution = new EvolutionApiService();
        $this->brevo     = new BrevoService();
    }

    /**
     * Entrega o pitch pronto para o USUÁRIO aplicar na vaga.
     * WhatsApp: mensagem formatada + link da vaga + CV (se tiver).
     * Email: modelo de candidatura pronto para copiar.
     */
    public function sendJobAlertToUser(User $user, array $jobData, array $aiData)
    {
        $whatsapp = $user->profile->whatsapp_number ?? null;
        $jobUrl   = $jobData['job_url'] ?? null;
        $cvUrl    = $user->profile->cv_path
                        ? asset('storage/' . $user->profile->cv_path)
                        : null;

        // ── WhatsApp ──────────────────────────────────────────────────────────
        if ($whatsapp) {
            $msg  = "🎯 *JobBot AI — Nova Vaga Encontrada!*\n\n";
            $msg .= "*{$jobData['title']}* — {$jobData['company_name']}\n";
            $msg .= "📍 {$jobData['location']}  |  via {$jobData['via']}\n\n";
            $msg .= "───────────────────────\n";
            $msg .= "💬 *Seu Pitch (pronto para enviar):*\n\n";
            $msg .= $aiData['pitch'] . "\n\n";
            $msg .= "───────────────────────\n";
            $msg .= "🧠 *Estratégia da IA:* {$aiData['strategy']}\n";
            $msg .= "📊 *Compatibilidade:* {$aiData['match']}%\n\n";

            if ($jobUrl) {
                $msg .= "🔗 *Aplicar na vaga:*\n{$jobUrl}\n\n";
            }

            $msg .= "_Copie o pitch acima e envie para o recrutador ao aplicar!_";

            $this->evolution->sendMessage(self::SYSTEM_INSTANCE, $whatsapp, $msg);

            // Envia o CV em PDF logo depois, se existir
            if ($cvUrl) {
                $this->evolution->sendMedia(
                    self::SYSTEM_INSTANCE,
                    $whatsapp,
                    $cvUrl,
                    'Seu Currículo — pronto para anexar na candidatura',
                    'curriculo.pdf'
                );
            }
        }

        // ── Email ─────────────────────────────────────────────────────────────
        $this->brevo->sendJobAlertEmail($user, $jobData, $aiData);
    }

    /**
     * Notifica o usuário quando uma candidatura foi registrada (autopilot).
     */
    public function notifyApplicationSent(Application $application)
    {
        $user     = $application->user;
        $whatsapp = $user->profile->whatsapp_number ?? null;

        if (!$whatsapp) return;

        $msg  = "✅ *JobBot AI: Candidatura Registrada!*\n\n";
        $msg .= "Empresa: *{$application->company_name}*\n";
        $msg .= "📊 Match: {$application->match_score}%\n";
        $msg .= "💡 Estratégia: {$application->strategy_note}\n\n";
        $msg .= "Acompanhe no painel: " . route('dashboard');

        $this->evolution->sendMessage(self::SYSTEM_INSTANCE, $whatsapp, $msg);
    }

    /**
     * Notifica o usuário após confirmação de pagamento.
     */
    public function notifyPaymentConfirmed(User $user, $amount)
    {
        if ($user->profile && $user->profile->whatsapp_number) {
            $valor = number_format($amount, 2, ',', '.');
            $msg   = "✅ *Pagamento Confirmado!*\n\n";
            $msg  .= "Olá {$user->name}, seu pagamento de R$ {$valor} foi aprovado.\n";
            $msg  .= "Sua conta agora é *PREMIUM* com créditos adicionados!\n\n";
            $msg  .= "Acesse o painel e ative o Piloto Automático:\n" . route('dashboard');

            $this->evolution->sendMessage(self::SYSTEM_INSTANCE, $user->profile->whatsapp_number, $msg);
        }

        $this->brevo->sendEmail(
            $user->email,
            $user->name,
            'Pagamento Confirmado — JobBot AI Premium',
            "<h2>Parabéns, {$user->name}!</h2>
             <p>Seu pagamento foi confirmado e sua conta Premium está ativa.</p>
             <p><a href='" . route('dashboard') . "'>Acessar o painel agora</a></p>"
        );
    }

    /**
     * Notifica o usuário quando o Piloto Automático é ativado.
     */
    public function notifyAutopilotStarted(User $user)
    {
        if ($user->profile && $user->profile->whatsapp_number) {
            $cargo = $user->profile->target_role ?? 'sua área';
            $msg   = "🤖 *Piloto Automático Ativado!*\n\n";
            $msg  .= "Sua IA começou a varrer a internet em busca de vagas para *{$cargo}*.\n\n";
            $msg  .= "Você receberá aqui os pitches prontos para cada vaga encontrada. É só copiar e aplicar!";

            $this->evolution->sendMessage(self::SYSTEM_INSTANCE, $user->profile->whatsapp_number, $msg);
        }
    }
}
