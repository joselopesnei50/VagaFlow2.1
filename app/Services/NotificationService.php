<?php

namespace App\Services;

use App\Models\User;
use App\Models\Application;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $evolution;
    protected $brevo;

    const SYSTEM_INSTANCE = 'jobbot_system';

    public function __construct()
    {
        $this->evolution = new EvolutionApiService();
        $this->brevo     = new BrevoService();
    }

    /**
     * Notifica o CANDIDATO que a candidatura foi disparada para a empresa.
     * Informa se foi via e-mail, WhatsApp ou se o contato não foi encontrado.
     */
    public function notifyApplicationSent(Application $application, array $contact = [])
    {
        $user     = $application->user;
        $whatsapp = $user->profile->whatsapp_number ?? null;

        if (!$whatsapp) return;

        $company  = $application->company_name;
        $hasEmail = !empty($contact['email']);
        $hasPhone = !empty($contact['phone']);

        if ($hasEmail || $hasPhone) {
            $channels = implode(' e ', array_filter([
                $hasEmail ? "📧 e-mail ({$contact['email']})" : null,
                $hasPhone ? "📲 WhatsApp ({$contact['phone']})" : null,
            ]));

            $msg  = "✅ *Candidatura Enviada!*\n\n";
            $msg .= "Sua candidatura para *{$company}* foi disparada com sucesso!\n\n";
            $msg .= "🎯 Canal utilizado: {$channels}\n";
            $msg .= "📊 Compatibilidade: {$application->match_score}%\n\n";
            $msg .= "Acompanhe no painel: " . route('dashboard');
        } else {
            // Contato não encontrado — orienta o usuário
            $jobUrl = $application->contact_info ?? null;
            $msg    = "⚠️ *Atenção — {$company}*\n\n";
            $msg   .= "Não conseguimos encontrar o e-mail ou WhatsApp do RH desta empresa automaticamente.\n\n";
            $msg   .= "✏️ *Seu pitch está pronto:*\n";
            $msg   .= $application->ai_message . "\n\n";
            if ($jobUrl && filter_var($jobUrl, FILTER_VALIDATE_URL)) {
                $msg .= "🔗 Aplique diretamente: {$jobUrl}\n\n";
            }
            $msg .= "Copie o pitch acima e envie pelo formulário da vaga ou LinkedIn!";
        }

        $instance = get_setting('evolution_system_instance', self::SYSTEM_INSTANCE);
        $this->evolution->sendMessage($instance, $whatsapp, $msg);
    }

    /**
     * Notifica o usuário após confirmação de pagamento.
     */
    public function notifyPaymentConfirmed(User $user, $amount)
    {
        $instance = get_setting('evolution_system_instance', self::SYSTEM_INSTANCE);

        if ($user->profile && $user->profile->whatsapp_number) {
            $valor = number_format($amount, 2, ',', '.');
            $msg   = "✅ *Pagamento Confirmado!*\n\n";
            $msg  .= "Olá {$user->name}! Seu pagamento de R$ {$valor} foi aprovado.\n";
            $msg  .= "Sua conta agora é *PREMIUM* — créditos adicionados!\n\n";
            $msg  .= "Acesse o painel: " . route('dashboard');

            $this->evolution->sendMessage($instance, $user->profile->whatsapp_number, $msg);
        }

        $this->brevo->sendEmail(
            $user->email,
            $user->name,
            'Pagamento Confirmado — ' . config('app.name'),
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
        $instance = get_setting('evolution_system_instance', self::SYSTEM_INSTANCE);

        if ($user->profile && $user->profile->whatsapp_number) {
            $cargo = $user->profile->target_role ?? 'sua área';
            $msg   = "🤖 *Piloto Automático Ativado!*\n\n";
            $msg  .= "Sua IA começou a buscar vagas para *{$cargo}*.\n\n";
            $msg  .= "Vou disparar as candidaturas automaticamente e te avisar aqui a cada envio.";

            $this->evolution->sendMessage($instance, $user->profile->whatsapp_number, $msg);
        }
    }
}
