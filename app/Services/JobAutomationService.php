<?php

namespace App\Services;

use App\Models\User;
use App\Models\Application;
use Illuminate\Support\Facades\Log;

class JobAutomationService
{
    protected $gemini;
    protected $evolution;
    protected $brevo;
    protected $notifier;
    protected $contactExtractor;

    public function __construct()
    {
        $this->gemini           = new GeminiService();
        $this->evolution        = new EvolutionApiService();
        $this->brevo            = new BrevoService();
        $this->notifier         = new NotificationService();
        $this->contactExtractor = new ContactExtractorService();
    }

    /**
     * Passo 1: Gera preview do pitch via IA — sem salvar ou enviar.
     */
    public function generatePreview(User $user, array $jobData): ?array
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
            - Descrição: " . mb_substr($jobData['description'] ?? '', 0, 600) . "

            Gere APENAS o JSON abaixo (sem texto extra, sem markdown):
            {
              \"pitch\": \"Mensagem profissional para o recrutador via WhatsApp (máximo 350 caracteres). Mencione o cargo, uma conquista relevante e demonstre interesse genuíno na empresa.\",
              \"email_body\": \"Corpo do e-mail de candidatura formal (máximo 5 parágrafos curtos). Tom profissional. Apresente-se, mencione a vaga, destaque 2-3 pontos fortes relevantes e agradeça.\",
              \"subject\": \"Assunto do e-mail (máximo 80 caracteres)\",
              \"strategy\": \"O que foi destacado (máximo 15 palavras)\",
              \"match\": 85
            }
        ";

        $aiRaw = $this->gemini->generateContent($prompt);
        if (!$aiRaw) return null;

        try {
            $clean  = preg_replace('/```json|```/', '', $aiRaw);
            $data   = json_decode(trim($clean), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("Gemini JSON parse error: " . json_last_error_msg());
                return null;
            }

            return $data;
        } catch (\Exception $e) {
            Log::error("generatePreview error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Passo 2: Busca o contato da empresa, envia candidatura por e-mail e WhatsApp,
     * registra no banco e notifica o usuário sobre o disparo.
     */
    public function finalizeAndSend(User $user, array $jobData, array $aiData): bool
    {
        $profile = $user->profile;

        // 1. Encontrar contato da empresa (email + telefone do RH)
        $contact = $this->contactExtractor->find($jobData);
        $hrEmail = $contact['email'] ?? null;
        $hrPhone = $contact['phone'] ?? null;

        Log::info("Contato encontrado para {$jobData['company_name']}: email={$hrEmail}, phone={$hrPhone}, source={$contact['source']}");

        // 2. Registrar candidatura no banco
        $application = Application::create([
            'user_id'       => $user->id,
            'company_name'  => $jobData['company_name'],
            'contact_info'  => $hrEmail ?? $hrPhone ?? $jobData['job_url'] ?? 'N/A',
            'status'        => 'pending',
            'ai_message'    => $aiData['pitch'],
            'strategy_note' => $aiData['strategy'],
            'match_score'   => $aiData['match'],
        ]);

        $sent = false;

        // 3. Enviar e-mail para o RH da empresa
        if ($hrEmail) {
            $emailSent = $this->brevo->sendApplicationEmail(
                hrEmail:    $hrEmail,
                company:    $jobData['company_name'],
                subject:    $aiData['subject'] ?? "Candidatura: {$profile->target_role} — {$user->name}",
                body:       $aiData['email_body'] ?? $aiData['pitch'],
                candidate:  $user,
                cvUrl:      $profile->cv_path ? asset('storage/' . $profile->cv_path) : null
            );

            if ($emailSent) {
                Log::info("E-mail de candidatura enviado para {$hrEmail} ({$jobData['company_name']})");
                $sent = true;
            }
        }

        // 4. Enviar WhatsApp para o número do RH (se encontrado)
        if ($hrPhone) {
            $instance = get_setting('evolution_system_instance', 'jobbot_system');

            $whatsappMsg  = "Olá! Meu nome é *{$user->name}*.\n\n";
            $whatsappMsg .= $aiData['pitch'] . "\n\n";

            if ($profile->cv_path) {
                $cvUrl        = asset('storage/' . $profile->cv_path);
                $whatsappMsg .= "Segue meu currículo em anexo. Fico à disposição!\n\n";
                $whatsappMsg .= "📧 {$user->email}";

                $this->evolution->sendMessage($instance, $hrPhone, $whatsappMsg);
                $this->evolution->sendMedia($instance, $hrPhone, $cvUrl, 'Currículo — ' . $user->name, 'curriculo.pdf');
            } else {
                $whatsappMsg .= "📧 {$user->email}";
                $this->evolution->sendMessage($instance, $hrPhone, $whatsappMsg);
            }

            Log::info("WhatsApp de candidatura enviado para {$hrPhone} ({$jobData['company_name']})");
            $sent = true;
        }

        // 5. Atualizar status e notificar o candidato
        $finalStatus = $sent ? 'sent' : 'pending';
        $application->update([
            'status'      => $finalStatus,
            'contact_info'=> $hrEmail ?? $hrPhone ?? $application->contact_info,
            'sent_at'     => $sent ? now() : null,
        ]);

        // Notifica o USUÁRIO que a candidatura foi disparada (ou que não encontrou contato)
        $this->notifier->notifyApplicationSent($application, $contact);

        return true;
    }
}
