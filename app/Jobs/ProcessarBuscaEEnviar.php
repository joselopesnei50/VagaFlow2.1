<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\JobScraperService;
use App\Services\JobAutomationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessarBuscaEEnviar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $cargo;
    protected $cidade;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, $cargo, $cidade)
    {
        $this->user = $user;
        $this->cargo = $cargo;
        $this->cidade = $cidade;
    }

    /**
     * Execute the job.
     */
    public function handle(JobScraperService $scraper, JobAutomationService $automation)
    {
        Log::info("Iniciando busca automatizada para o usuário: {$this->user->name} ({$this->cargo} em {$this->cidade})");

        // 1. Busca vagas reais
        $vagas = $scraper->searchJobs($this->cargo, $this->cidade, $this->user);

        // 2. Define o limite de processamento (Menor valor entre o configurado e os créditos disponíveis)
        $limit = min($this->user->profile->auto_limit ?? 5, $this->user->credits);
        Log::info("Limite definido para processamento: {$limit} candidaturas.");

        if ($limit <= 0) {
            Log::info("Usuário sem créditos ou limite 0. Cancelando envio.");
            return;
        }

        // 3. Processa as vagas encontradas até o limite
        foreach (array_slice($vagas, 0, $limit) as $vaga) {
            try {
                // Gera a análise da IA para esta vaga específica
                $analysis = $automation->generatePreview($this->user, $vaga);

                if ($analysis) {
                    // Envia a candidatura (WhatsApp/Email)
                    $automation->finalizeAndSend($this->user, $vaga, $analysis);
                    
                    Log::info("Candidatura enviada automaticamente para: {$vaga['company_name']}");
                }
            } catch (\Exception $e) {
                Log::error("Erro ao processar vaga da {$vaga['company_name']}: " . $e->getMessage());
            }
        }
    }
}
