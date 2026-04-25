<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GoogleMapsService
{
    protected $apiKey;
    protected $baseUrl = 'https://maps.googleapis.com/maps/api/place';

    public function __construct()
    {
        $this->apiKey = get_setting('google_maps_api_key') ?: env('GOOGLE_MAPS_API_KEY');
    }

    /**
     * Busca empresas de um setor/cargo em uma cidade usando Google Places.
     * Retorna empresas com nome, telefone e site — prontos para prospecção direta.
     */
    public function searchCompanies(string $sector, string $city, int $limit = 10): array
    {
        if (!$this->apiKey) {
            Log::warning('GoogleMapsService: API key não configurada.');
            return [];
        }

        $cacheKey = 'gmaps_' . md5("{$sector}_{$city}");

        return Cache::remember($cacheKey, now()->addHours(6), function () use ($sector, $city, $limit) {
            $query = "empresas de {$sector} em {$city}";

            try {
                // 1. Text Search — encontra lista de empresas
                $searchResponse = Http::timeout(10)->get("{$this->baseUrl}/textsearch/json", [
                    'query'    => $query,
                    'language' => 'pt-BR',
                    'region'   => 'br',
                    'key'      => $this->apiKey,
                ]);

                if (!$searchResponse->successful()) {
                    Log::error('Google Maps TextSearch error: ' . $searchResponse->body());
                    return [];
                }

                $places = $searchResponse->json()['results'] ?? [];

                // 2. Para cada lugar, buscar detalhes (telefone + site)
                $results = [];
                foreach (array_slice($places, 0, $limit) as $place) {
                    $details = $this->getPlaceDetails($place['place_id']);

                    $phone   = $details['formatted_phone_number'] ?? null;
                    $website = $details['website'] ?? null;
                    $address = $place['formatted_address'] ?? $city;

                    // Formatar telefone para WhatsApp (apenas dígitos, com DDI 55)
                    $whatsappPhone = $this->formatPhoneForWhatsApp($phone);

                    $results[] = [
                        'title'         => 'Prospecção: ' . $sector,
                        'company_name'  => $place['name'],
                        'location'      => $address,
                        'description'   => "Empresa encontrada via Google Maps em {$city}. Setor: {$sector}. Endereço: {$address}.",
                        'thumbnail'     => $this->getPlacePhoto($place) ?? 'https://ui-avatars.com/api/?name=' . urlencode($place['name']) . '&background=0F9D58&color=fff',
                        'via'           => 'Google Maps',
                        'job_id'        => 'maps_' . $place['place_id'],
                        'job_url'       => 'https://www.google.com/maps/place/?q=place_id:' . $place['place_id'],
                        'contact_phone' => $whatsappPhone,
                        'contact_email' => $this->inferEmail($website, $place['name']),
                        'website'       => $website,
                        'maps_rating'   => $place['rating'] ?? null,
                    ];
                }

                return $results;

            } catch (\Exception $e) {
                Log::error('GoogleMapsService exception: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Busca detalhes de um lugar específico (telefone + site).
     */
    private function getPlaceDetails(string $placeId): array
    {
        try {
            $response = Http::timeout(8)->get("{$this->baseUrl}/details/json", [
                'place_id' => $placeId,
                'fields'   => 'formatted_phone_number,website,name',
                'language' => 'pt-BR',
                'key'      => $this->apiKey,
            ]);

            return $response->successful()
                ? ($response->json()['result'] ?? [])
                : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Formata telefone para uso no WhatsApp (DDI 55 + DDD + número).
     */
    private function formatPhoneForWhatsApp(?string $phone): ?string
    {
        if (!$phone) return null;

        $digits = preg_replace('/\D/', '', $phone);

        // Adiciona DDI 55 se não tiver
        if (strlen($digits) === 11) return '55' . $digits; // celular com DDD
        if (strlen($digits) === 10) return '55' . $digits; // fixo com DDD
        if (strlen($digits) === 13 && str_starts_with($digits, '55')) return $digits;

        return null;
    }

    /**
     * Infere email de RH a partir do site da empresa.
     */
    private function inferEmail(?string $website, string $companyName): ?string
    {
        if ($website) {
            $host = parse_url($website, PHP_URL_HOST);
            $host = str_replace('www.', '', $host ?? '');
            if ($host && str_contains($host, '.')) {
                return 'rh@' . $host;
            }
        }

        // Fallback: nome da empresa sem espaços + .com.br
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $companyName));
        return strlen($slug) >= 3 ? 'rh@' . $slug . '.com.br' : null;
    }

    /**
     * Retorna URL da foto do lugar via Google Places Photo API.
     */
    private function getPlacePhoto(array $place): ?string
    {
        $ref = $place['photos'][0]['photo_reference'] ?? null;
        if (!$ref) return null;

        return "{$this->baseUrl}/photo?maxwidth=80&photo_reference={$ref}&key={$this->apiKey}";
    }
}
