<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class PublicPageController extends Controller
{
    public function privacy()
    {
        $content = Setting::where('key', 'privacy_policy')->first()?->value ?? 'O conteúdo da Política de Privacidade será inserido aqui pelo administrador.';
        return view('public.privacy', compact('content'));
    }

    public function terms()
    {
        $content = Setting::where('key', 'terms_of_service')->first()?->value ?? 'O conteúdo dos Termos de Uso será inserido aqui pelo administrador.';
        return view('public.terms', compact('content'));
    }
}
