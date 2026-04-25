<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.brevo.com/v3';

    public function __construct()
    {
        $this->apiKey = get_setting('brevo_api_key') ?: env('BREVO_API_KEY');
    }

    /**
     * E-mail de candidatura enviado PARA O RH DA EMPRESA.
     * Remetente: sistema (domínio verificado no Brevo)
     * Reply-To: e-mail do candidato (para o recrutador responder direto para ele)
     */
    public function sendApplicationEmail(
        string  $hrEmail,
        string  $company,
        string  $subject,
        string  $body,
        User    $candidate,
        ?string $cvUrl = null
    ): bool {
        $senderEmail = get_setting('brevo_sender_email') ?: env('BREVO_SENDER_EMAIL', 'vagas@jobbot.ai');
        $senderName  = get_setting('brevo_sender_name')  ?: config('app.name', 'JobBot AI');

        $bodyHtml = $this->buildApplicationHtml($body, $candidate, $cvUrl);

        $payload = [
            'sender'  => ['name' => $candidate->name . ' via ' . $senderName, 'email' => $senderEmail],
            'to'      => [['email' => $hrEmail, 'name' => 'Recrutador(a) ' . $company]],
            'replyTo' => ['email' => $candidate->email, 'name' => $candidate->name],
            'subject' => $subject,
            'htmlContent' => $bodyHtml,
        ];

        return $this->send($payload);
    }

    /**
     * E-mail genérico (notificações internas, confirmação de pagamento, etc.)
     */
    public function sendEmail(string $toEmail, string $toName, string $subject, string $contentHtml): bool
    {
        $senderEmail = get_setting('brevo_sender_email') ?: env('BREVO_SENDER_EMAIL', 'noreply@jobbot.ai');
        $senderName  = get_setting('brevo_sender_name')  ?: config('app.name', 'JobBot AI');

        return $this->send([
            'sender'      => ['name' => $senderName, 'email' => $senderEmail],
            'to'          => [['email' => $toEmail, 'name' => $toName]],
            'subject'     => $subject,
            'htmlContent' => $contentHtml,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function buildApplicationHtml(string $body, User $candidate, ?string $cvUrl): string
    {
        $bodyHtml   = nl2br(htmlspecialchars($body));
        $cvSection  = $cvUrl
            ? "<p style='margin:24px 0 0;'>
                 <a href='{$cvUrl}' style='display:inline-block;background:#2563eb;color:#fff;padding:12px 24px;
                    border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;'>
                   📄 Baixar Currículo
                 </a>
               </p>"
            : '';

        return "
        <div style='font-family:Inter,Arial,sans-serif;max-width:640px;margin:0 auto;'>
            <div style='padding:32px 32px 24px;'>
                <p style='font-size:15px;line-height:1.7;color:#1e293b;white-space:pre-line;'>{$bodyHtml}</p>
                {$cvSection}
                <hr style='margin:32px 0;border:none;border-top:1px solid #e2e8f0;'>
                <p style='font-size:13px;color:#64748b;'>
                    <strong>{$candidate->name}</strong><br>
                    <a href='mailto:{$candidate->email}' style='color:#2563eb;'>{$candidate->email}</a>
                </p>
            </div>
            <div style='background:#f8fafc;padding:16px 32px;border-top:1px solid #e2e8f0;'>
                <p style='margin:0;font-size:11px;color:#94a3b8;'>
                    Candidatura enviada via <strong>JobBot AI</strong>.
                    Para responder, utilize o e-mail do candidato acima.
                </p>
            </div>
        </div>";
    }

    private function send(array $payload): bool
    {
        if (!$this->apiKey) {
            Log::warning('Brevo: API key não configurada.');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'api-key'      => $this->apiKey,
                'content-type' => 'application/json',
                'accept'       => 'application/json',
            ])->post("{$this->baseUrl}/smtp/email", $payload);

            if (!$response->successful()) {
                Log::error('Brevo send error: ' . $response->body());
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Brevo exception: ' . $e->getMessage());
            return false;
        }
    }
}
