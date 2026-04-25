<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SearchLog;

class JobScraperService
{
    protected $serperKey;
    protected $jsearchKey;

    public function __construct()
    {
        $this->serperKey = get_setting('serper_api_key');
        $this->jsearchKey = get_setting('jsearch_api_key'); // RapidAPI Key
    }

    /**
     * Search for jobs using JSearch (RapidAPI) - Specialized in Job Aggregation
     */
    public function searchJobs($query, $location = 'Brasil', $user = null)
    {
        $userId = $user ? $user->id : auth()->id();

        if (!$this->jsearchKey) {
            return $this->searchViaSerper($query, $location, $user);
        }

        try {
            $response = Http::withHeaders([
                'X-RapidAPI-Key' => $this->jsearchKey,
                'X-RapidAPI-Host' => 'jsearch.p.rapidapi.com'
            ])->get('https://jsearch.p.rapidapi.com/search', [
                'query' => "{$query} em {$location}",
                'num_pages' => '1',
                'date_posted' => 'all'
            ]);

            if ($response->successful()) {
                $results = $response->json()['data'] ?? [];
                
                SearchLog::create([
                    'user_id' => $userId,
                    'query' => $query,
                    'location' => $location,
                    'service' => 'jsearch',
                    'status' => 'success',
                    'results_count' => count($results)
                ]);

                return array_map(function($job) {
                    return [
                        'title'        => $job['job_title'],
                        'company_name' => $job['employer_name'],
                        'location'     => trim(($job['job_city'] ?? '') . ' ' . ($job['job_state'] ?? '')),
                        'description'  => $job['job_description'],
                        'thumbnail'    => $job['employer_logo'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($job['employer_name']),
                        'via'          => $job['job_publisher'] ?? 'Web',
                        'job_id'       => $job['job_id'],
                        'job_url'      => $job['job_apply_link'] ?? $job['job_google_link'] ?? null,
                    ];
                }, array_slice($results, 0, 10));
            }

            throw new \Exception("JSearch API Error: " . $response->body());

        } catch (\Exception $e) {
            SearchLog::create([
                'user_id' => $userId,
                'query' => $query,
                'location' => $location,
                'service' => 'jsearch',
                'status' => 'error',
                'error_message' => $e->getMessage()
            ]);
            Log::error("JSearch Error: " . $e->getMessage());
            return $this->searchViaSerper($query, $location);
        }
    }

    /**
     * Search via Serper.dev (Google Search API) - More flexible for "Cold Prospecting"
     */
    public function searchViaSerper($query, $location = 'Brasil', $user = null)
    {
        $userId = $user ? $user->id : auth()->id();

        if (!$this->serperKey) {
            return $this->getMockJobs($query);
        }

        try {
            $response = Http::withHeaders([
                'X-API-KEY' => $this->serperKey,
                'Content-Type' => 'application/json',
            ])->post('https://google.serper.dev/search', [
                'q' => "vagas de emprego para {$query} em {$location}",
                'gl' => 'br',
                'hl' => 'pt-br',
            ]);

            if ($response->successful()) {
                $results = $response->json()['organic'] ?? [];

                SearchLog::create([
                    'user_id' => $userId,
                    'query' => $query,
                    'location' => $location,
                    'service' => 'serper',
                    'status' => 'success',
                    'results_count' => count($results)
                ]);

                return array_map(function($res) {
                    return [
                        'title'        => $res['title'],
                        'company_name' => $this->extractCompanyName($res['title']),
                        'location'     => 'Brasil',
                        'description'  => $res['snippet'],
                        'thumbnail'    => 'https://ui-avatars.com/api/?name=JOB',
                        'via'          => parse_url($res['link'], PHP_URL_HOST),
                        'job_id'       => md5($res['link']),
                        'job_url'      => $res['link'],
                    ];
                }, array_slice($results, 0, 10));
            }

            throw new \Exception("Serper API Error: " . $response->body());

        } catch (\Exception $e) {
            SearchLog::create([
                'user_id' => $userId,
                'query' => $query,
                'location' => $location,
                'service' => 'serper',
                'status' => 'error',
                'error_message' => $e->getMessage()
            ]);
            Log::error("Serper Error: " . $e->getMessage());
            return $this->getMockJobs($query);
        }
    }

    private function extractCompanyName($title)
    {
        $parts = explode('-', $title);
        return trim($parts[1] ?? $parts[0]);
    }

    private function getMockJobs($query)
    {
        return [
            [
                'title'        => $query . ' Senior',
                'company_name' => 'Tech Solutions Corp',
                'location'     => 'São Paulo, SP',
                'description'  => 'Buscamos especialista em ' . $query . ' para projeto escalável.',
                'thumbnail'    => 'https://ui-avatars.com/api/?name=TS&background=0D8ABC&color=fff',
                'via'          => 'LinkedIn',
                'job_id'       => 'mock_1',
                'job_url'      => 'https://www.linkedin.com/jobs/',
            ],
            [
                'title'        => 'Especialista ' . $query,
                'company_name' => 'Inovação Digital',
                'location'     => 'Remoto',
                'description'  => 'Oportunidade 100% remota para atuar com ' . $query . ' e nuvem.',
                'thumbnail'    => 'https://ui-avatars.com/api/?name=ID&background=4f46e5&color=fff',
                'via'          => 'Indeed',
                'job_id'       => 'mock_2',
                'job_url'      => 'https://br.indeed.com/',
            ],
        ];
    }
}
