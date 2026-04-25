<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JobAutomationService;
use App\Services\JobScraperService;
use App\Models\User;

class CvController extends Controller
{
    protected $automation;
    protected $scraper;
    protected $notifier;

    public function __construct()
    {
        $this->automation = new JobAutomationService();
        $this->scraper = new JobScraperService();
        $this->notifier = new \App\Services\NotificationService();
    }

    public function upload(Request $request)
    {
        $request->validate([
            'cv' => 'required|mimes:pdf,doc,docx|max:5120',
            'target_role' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'bio' => 'nullable|string',
        ]);

        $user = auth()->user();
        $path = $request->file('cv')->store('cvs', 'public');

        \App\Models\UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'cv_path' => $path,
                'target_role' => $request->target_role,
                'whatsapp_number' => $request->whatsapp_number,
                'bio' => $request->bio,
                'auto_limit' => $request->auto_limit ?? 5
            ]
        );

        return redirect()->route('dashboard')->with('success', 'Currículo enviado com sucesso!');
    }

    /**
     * Step 0: Search for active jobs
     */
    public function search(Request $request)
    {
        $user = auth()->user();
        $query = $user->profile->target_role ?? 'Desenvolvedor';
        
        $jobs = $this->scraper->searchJobs($query);

        return response()->json($jobs);
    }

    /**
     * Step 1: Analyze and generate preview for a specific job
     */
    public function analyze(Request $request)
    {
        $user = auth()->user();
        
        if ($user->credits <= 0) {
            return response()->json(['error' => 'Créditos insuficientes.'], 403);
        }

        $companyData = [
            'name'         => $request->company_name ?? 'Empresa',
            'company_name' => $request->company_name ?? 'Empresa',
            'title'        => $request->title ?? 'Vaga',
            'description'  => $request->description ?? '',
            'location'     => $request->location ?? 'Brasil',
            'via'          => $request->via ?? 'Web',
            'job_url'      => $request->job_url ?? null,
        ];

        $analysis = $this->automation->generatePreview($user, $companyData);

        if (!$analysis) {
            return response()->json(['error' => 'Falha na análise da IA.'], 500);
        }

        return response()->json($analysis);
    }

    /**
     * Step 2: Confirm and Send
     */
    public function send(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'company_name' => 'required|string',
            'pitch' => 'required|string',
            'strategy' => 'required|string',
            'match' => 'required|integer',
        ]);

        $companyData = [
            'name'         => $request->company_name,
            'title'        => $request->title ?? '',
            'company_name' => $request->company_name,
            'location'     => $request->location ?? 'Brasil',
            'via'          => $request->via ?? 'Web',
            'description'  => $request->description ?? '',
            'job_url'      => $request->job_url ?? null,
        ];

        $aiData = [
            'pitch' => $request->pitch,
            'strategy' => $request->strategy,
            'match' => $request->match,
        ];

        $success = $this->automation->finalizeAndSend($user, $companyData, $aiData);

        if ($success) {
            $user->decrement('credits');
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Erro ao enviar.'], 500);
    }

    public function autopilot(Request $request)
    {
        $user = auth()->user();
        
        if ($user->credits <= 0) {
            return response()->json(['error' => 'Créditos insuficientes.'], 403);
        }

        $query = $user->profile->target_role ?? 'Desenvolvedor';
        $location = 'Brasil';

        \App\Jobs\ProcessarBuscaEEnviar::dispatch($user, $query, $location);

        // Notificar o Usuário
        $this->notifier->notifyAutopilotStarted($user);

        return response()->json(['success' => true]);
    }
}
