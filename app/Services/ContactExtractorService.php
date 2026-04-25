<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ContactExtractorService
{
    protected $serperKey;

    // Domínios de job boards que NÃO são o site da empresa — ignorar
    const JOB_BOARD_DOMAINS = [
        'linkedin.com', 'indeed.com', 'glassdoor.com', 'infojobs.com.br',
        'catho.com.br', 'vagas.com.br', 'empregare.com', 'trampos.co',
        'programathor.com.br', 'gupy.io', 'kenoby.com', 'workable.com',
        'lever.co', 'greenhouse.io', 'jobs.com.br', 'sine.com.br',
    ];

    public function __construct()
    {
        $this->serperKey = get_setting('serper_api_key') ?: env('SERPER_API_KEY');
    }

    /**
     * Ponto de entrada principal.
     * Tenta extrair contato da empresa por múltiplas estratégias em ordem de prioridade.
     *
     * Retorna: ['email' => '...', 'phone' => '...', 'source' => '...']
     */
    public function find(array $jobData): array
    {
        $company = $jobData['company_name'];
        $cacheKey = 'contact_' . md5(strtolower($company));

        return Cache::remember($cacheKey, now()->addHours(12), function () use ($jobData, $company) {
            // 1. Extrair do texto da própria descrição da vaga
            $fromDescription = $this->extractFromText($jobData['description'] ?? '');
            if ($fromDescription['email'] || $fromDescription['phone']) {
                return array_merge($fromDescription, ['source' => 'job_description']);
            }

            // 2. Buscar via Serper: "empresa RH email candidatura"
            if ($this->serperKey) {
                $fromSerper = $this->searchViaSerper($company);
                if ($fromSerper['email'] || $fromSerper['phone']) {
                    return array_merge($fromSerper, ['source' => 'serper_search']);
                }
            }

            // 3. Inferir email genérico de RH a partir do domínio da empresa
            $inferredEmail = $this->inferHrEmail($company, $jobData['job_url'] ?? null);
            if ($inferredEmail) {
                return ['email' => $inferredEmail, 'phone' => null, 'source' => 'inferred'];
            }

            return ['email' => null, 'phone' => null, 'source' => 'not_found'];
        });
    }

    /**
     * Extrai emails e telefones brasileiros do texto via regex.
     */
    public function extractFromText(string $text): array
    {
        $email = null;
        $phone = null;

        // Email
        if (preg_match('/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/', $text, $m)) {
            $candidate = strtolower($m[0]);
            // Ignorar emails de exemplo ou domínios de job boards
            $ignoreDomains = array_merge(self::JOB_BOARD_DOMAINS, ['example.com', 'email.com', 'test.com']);
            $domain = explode('@', $candidate)[1] ?? '';
            $isJobBoard = collect($ignoreDomains)->contains(fn($d) => str_contains($domain, $d));
            if (!$isJobBoard) {
                $email = $candidate;
            }
        }

        // Telefone brasileiro (celular ou fixo, com ou sem DDD)
        if (preg_match('/(?:\+?55\s?)?(?:\(?\d{2}\)?[\s\-]?)(?:9\s?\d{4}|\d{4})[\s\-]?\d{4}/', $text, $m)) {
            $phone = preg_replace('/\D/', '', $m[0]);
            // Garantir formato 55 + DDD + número
            if (strlen($phone) === 11) {
                $phone = '55' . $phone;
            } elseif (strlen($phone) === 10) {
                $phone = '55' . $phone;
            }
        }

        return compact('email', 'phone');
    }

    /**
     * Usa Serper para buscar contato de RH da empresa no Google.
     */
    private function searchViaSerper(string $company): array
    {
        $queries = [
            "\"$company\" email RH vagas candidatura",
            "site:" . $this->guessDomain($company) . " email contato",
        ];

        foreach ($queries as $query) {
            try {
                $response = Http::timeout(8)->withHeaders([
                    'X-API-KEY'    => $this->serperKey,
                    'Content-Type' => 'application/json',
                ])->post('https://google.serper.dev/search', [
                    'q'  => $query,
                    'gl' => 'br',
                    'hl' => 'pt-br',
                    'num' => 3,
                ]);

                if (!$response->successful()) continue;

                $data = $response->json();
                $text = '';

                // Concatenar snippets e títulos
                foreach ($data['organic'] ?? [] as $r) {
                    $text .= ' ' . ($r['title'] ?? '') . ' ' . ($r['snippet'] ?? '');
                }

                // Tentar também o answer box / knowledge graph
                $text .= ' ' . ($data['answerBox']['answer'] ?? '');
                $text .= ' ' . ($data['answerBox']['snippet'] ?? '');

                $result = $this->extractFromText($text);
                if ($result['email'] || $result['phone']) {
                    return $result;
                }

            } catch (\Exception $e) {
                Log::warning("ContactExtractor Serper error: " . $e->getMessage());
            }
        }

        return ['email' => null, 'phone' => null];
    }

    /**
     * Infere email de RH com base no domínio da empresa ou URL da vaga.
     */
    private function inferHrEmail(string $company, ?string $jobUrl): ?string
    {
        // Tenta extrair domínio da URL da vaga (se for o site da empresa, não job board)
        if ($jobUrl) {
            $host = parse_url($jobUrl, PHP_URL_HOST) ?? '';
            $host = str_replace('www.', '', $host);
            $isJobBoard = collect(self::JOB_BOARD_DOMAINS)->contains(fn($d) => str_contains($host, $d));

            if (!$isJobBoard && $host && str_contains($host, '.')) {
                return 'rh@' . $host;
            }
        }

        // Tenta montar domínio a partir do nome da empresa
        $domain = $this->guessDomain($company);
        if ($domain) {
            return 'rh@' . $domain;
        }

        return null;
    }

    /**
     * Tenta adivinhar o domínio de uma empresa pelo nome.
     */
    private function guessDomain(string $company): ?string
    {
        // Remove sufixos comuns
        $clean = preg_replace('/\s+(S\.?A\.?|Ltda\.?|ME|EIRELI|Inc\.?|Corp\.?|LLC)$/i', '', $company);
        $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', $clean);
        $clean = strtolower(trim(str_replace(' ', '', $clean)));

        if (strlen($clean) < 3) return null;

        return $clean . '.com.br';
    }
}
