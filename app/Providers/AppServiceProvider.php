<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Força HTTPS em produção (necessário em Railway, Render, etc.)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
