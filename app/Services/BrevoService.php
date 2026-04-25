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
        $this->apiKey = get_setting('brevo_api_key') ?: env('BREVO_API_KEY');
    }

    /**
     * Email genérico.
     */
    public function sendEmail($toEmail, $toName, $subject, $contentHtml)
    {
        return $this->send($toEmail, $toName, $subject, $contentHtml);
    }

    /**
     * Email de alerta de vaga — entrega o pitch pronto para o usuário.
     */
    public function sendJobAlertEmail($user, array $jobData, array $aiData)
    {
        $jobUrl   = $jobData['job_url'] ?? null;
        $applyBtn = $jobUrl
            ? "<a href='{$jobUrl}' style='display:inline-block;background:#2563eb;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:bold;margin-top:16px;'>Aplicar na Vaga</a>"
            : '';

        $pitchEscaped = nl2br(htmlspecialchars($aiData['pitch']));

        $html = "
        <div style='font-family:Inter,Arial,sans-serif;max-width:600px;margin:0 auto;background:#f8fafc;padding:32px 16px;'>
            <div style='background:#1e40af;color:#fff;border-radius:16px 16px 0 0;padding:32px;text-align:center;'>
                <h1 style='margin:0;font-size:22px;font-weight:900;letter-spacing:-0.5px;'>🎯 JobBot AI — Nova Vaga!</h1>
                <p style='margin:8px 0 0;opacity:.8;font-size:13px;'>{$user->name}, encontramos uma oportunidade para você</p>
            </div>

            <div style='background:#fff;border-radius:0 0 16px 16px;padding:32px;'>
                <!-- Vaga -->
                <div style='background:#f1f5f9;border-radius:12px;padding:20px;margin-bottom:24px;'>
                    <p style='margin:0 0 4px;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.1em;color:#64748b;'>Vaga</p>
                    <h2 style='margin:0;font-size:18px;font-weight:900;color:#0f172a;'>{$jobData['title']}</h2>
                    <p style='margin:4px 0 0;font-size:14px;color:#475569;font-weight:600;'>{$jobData['company_name']} &nbsp;·&nbsp; {$jobData['location']}</p>
                    <p style='margin:4px 0 0;font-size:12px;color:#94a3b8;'>via {$jobData['via']}</p>
                    {$applyBtn}
                </div>

                <!-- Pitch -->
                <p style='margin:0 0 8px;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.1em;color:#64748b;'>💬 Seu Pitch (copie e envie ao recrutar)</p>
                <div style='background:#eff6ff;border:2px solid #bfdbfe;border-radius:12px;padding:20px;margin-bottom:24px;'>
                    <p style='margin:0;font-size:15px;line-height:1.6;color:#1e40af;font-weight:500;'>{$pitchEscaped}</p>
                </div>

                <!-- Estratégia -->
                <div style='display:flex;gap:16px;margin-bottom:24px;'>
                    <div style='flex:1;background:#f0fdf4;border-radius:12px;padding:16px;text-align:center;'>
                        <p style='margin:0 0 4px;font-size:10px;font-weight:900;text-transform:uppercase;color:#16a34a;letter-spacing:.1em;'>Compatibilidade</p>
                        <p style='margin:0;font-size:28px;font-weight:900;color:#15803d;'>{$aiData['match']}%</p>
                    </div>
                    <div style='flex:2;background:#faf5ff;border-radius:12px;padding:16px;'>
                        <p style='margin:0 0 4px;font-size:10px;font-weight:900;text-transform:uppercase;color:#7c3aed;letter-spacing:.1em;'>Estratégia da IA</p>
                        <p style='margin:0;font-size:13px;color:#6d28d9;font-style:italic;'>\"{$aiData['strategy']}\"</p>
                    </div>
                </div>

                <p style='margin:0;font-size:12px;color:#94a3b8;text-align:center;'>
                    Acompanhe todas as suas candidaturas no <a href='" . route('dashboard') . "' style='color:#2563eb;'>painel JobBot AI</a>.
                </p>
            </div>
        </div>";

        return $this->send(
            $user->email,
            $user->name,
            "🎯 [{$aiData['match']}% Match] {$jobData['title']} — {$jobData['company_name']}",
            $html
        );
    }

    /**
     * Método interno de envio.
     */
    private function send($toEmail, $toName, $subject, $contentHtml)
    {
        if (!$this->apiKey) {
            Log::warning('Brevo: API key não configurada.');
            return false;
        }

        try {
            $senderEmail = get_setting('brevo_sender_email') ?: ('noreply@' . config('app.name', 'jobbot') . '.ai');
            $senderName  = get_setting('brevo_sender_name')  ?: config('app.name', 'JobBot AI');

            $response = Http::withHeaders([
                'api-key'      => $this->apiKey,
                'content-type' => 'application/json',
                'accept'       => 'application/json',
            ])->post("{$this->baseUrl}/smtp/email", [
                'sender'      => ['name' => $senderName, 'email' => $senderEmail],
                'to'          => [['email' => $toEmail, 'name' => $toName]],
                'subject'     => $subject,
                'htmlContent' => $contentHtml,
            ]);

            if (!$response->successful()) {
                Log::error('Brevo send error: ' . $response->body());
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Brevo exception: ' . $e->getMessage());
            return false;
        }
    }
}
