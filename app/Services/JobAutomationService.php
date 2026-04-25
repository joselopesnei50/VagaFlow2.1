<?php

namespace App\Services;

use App\Models\User;
use App\Models\Application;
use Illuminate\Support\Facades\Log;

class JobAutomationService
{
    protected $gemini;
    protected $notifier;

    public function __construct()
    {
        $this->gemini   = new GeminiService();
        $this->notifier = new NotificationService();
    }

    /**
     * Passo 1: Gera preview do pitch via IA sem salvar ou enviar.
     */
    public function generatePreview(User $user, $jobData)
    {
        $profile = $user->profile;

        $prompt = "
            Você é um especialista em recrutamento e redação profissional.

            Candidato: {$profile->bio}
            Cargo desejado: {$profile->target_role}

            Vaga encontrada:
            - Título: {$jobData['title']}
            - Empresa: {$jobData['company_name']}
            - Localização: {$jobData['location']}
            - Descrição: " . mb_substr($jobData['description'], 0, 500) . "

            Gere APENAS o JSON abaixo (sem texto extra, sem markdown, sem blocos de código):
            {
              \"pitch\": \"Mensagem profissional e direta para WhatsApp (máximo 350 caracteres). Deve mencionar o cargo, uma conquista relevante do candidato e demonstrar interesse genuíno.\",
              \"strategy\": \"O que foi destacado na mensagem (máximo 15 palavras)\",
              \"match\": 85
            }
        ";

        $aiRawResponse = $this->gemini->generateContent($prompt);

        if (!$aiRawResponse) return null;

        try {
            $clean = preg_replace('/```json|```/', '', $aiRawResponse);
            $data  = json_decode(trim($clean), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("Gemini JSON parse error: " . json_last_error_msg() . " | Raw: " . $aiRawResponse);
                return null;
            }

            return $data;
        } catch (\Exception $e) {
            Log::error("JobAutomationService generatePreview error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Passo 2: Registra a candidatura e entrega o pitch para o USUÁRIO
     * via WhatsApp (Evolution API) e email (Brevo) — para que ele aplique.
     */
    public function finalizeAndSend(User $user, $jobData, $aiData)
    {
        // 1. Registra no banco
        $application = Application::create([
            'user_id'      => $user->id,
            'company_name' => $jobData['company_name'],
            'contact_info' => $jobData['job_url'] ?? $jobData['via'] ?? 'N/A',
            'status'       => 'sent',
            'ai_message'   => $aiData['pitch'],
            'strategy_note'=> $aiData['strategy'],
            'match_score'  => $aiData['match'],
            'sent_at'      => now(),
        ]);

        // 2. Envia o pitch para o WhatsApp e email DO USUÁRIO
        $this->notifier->sendJobAlertToUser($user, $jobData, $aiData);

        return true;
    }
}
