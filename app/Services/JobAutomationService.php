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

    public function __construct()
    {
        $this->gemini = new GeminiService();
        $this->evolution = new EvolutionApiService();
        $this->brevo = new BrevoService();
        $this->notifier = new NotificationService();
    }

    /**
     * Step 1: Generate AI Preview without sending
     */
    public function generatePreview(User $user, $companyData)
    {
        $profile = $user->profile;
        
        $prompt = "
            Aja como um recrutador especializado. 
            Candidato: [{$profile->bio}].
            Empresa Alvo: [{$companyData['name']}].
            
            Gere um JSON (apenas o objeto):
            {
              \"pitch\": \"Mensagem profissional curta para WhatsApp (máximo 300 caracteres)\",
              \"strategy\": \"Breve descrição do que foi enfatizado (máximo 15 palavras)\",
              \"match\": 85
            }
        ";
        
        $aiRawResponse = $this->gemini->generateContent($prompt);

        if (!$aiRawResponse) return null;

        try {
            $jsonString = preg_replace('/```json|```/', '', $aiRawResponse);
            return json_decode(trim($jsonString), true);
        } catch (\Exception $e) {
            Log::error("JSON Parse Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Step 2: Save to DB and Send
     */
    public function finalizeAndSend(User $user, $companyData, $aiData)
    {
        $profile = $user->profile;

        // 1. Create Application record
        $application = Application::create([
            'user_id' => $user->id,
            'company_name' => $companyData['name'],
            'contact_info' => $companyData['email'] ?? $companyData['phone'] ?? 'N/A',
            'status' => 'pending',
            'ai_message' => $aiData['pitch'],
            'strategy_note' => $aiData['strategy'],
            'match_score' => $aiData['match'],
        ]);

        // 2. Send via WhatsApp
        $messageId = null;
        if (isset($companyData['phone'])) {
            $instance = $user->name . '_inst'; 
            $resp = $this->evolution->sendMessage($instance, $companyData['phone'], $aiData['pitch']);
            $messageId = $resp['key']['id'] ?? null;

            if ($profile->cv_path) {
                $this->evolution->sendMedia($instance, $companyData['phone'], asset('storage/' . $profile->cv_path), 'Meu Currículo');
            }
        }

        // 3. Send via Email
        if (isset($companyData['email'])) {
            $this->brevo->sendEmail($companyData['email'], $companyData['name'], "Candidatura: {$profile->target_role} - {$user->name}", $aiData['pitch']);
        }

        // 4. Update Status
        $application->update([
            'status' => 'sent',
            'delivery_status' => 'sent',
            'message_id' => $messageId,
            'sent_at' => now(),
        ]);

        // Notificar o Usuário
        $this->notifier->notifyApplicationSent($application);

        return true;
    }
}
