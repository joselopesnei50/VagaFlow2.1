<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $applications = $user->applications()->latest()->take(10)->get();
        
        $stats = [
            'total' => $user->applications()->count(),
            'pending' => $user->applications()->where('status', 'pending')->count(),
            'sent' => $user->applications()->where('status', 'sent')->count(),
        ];

        return view('dashboard', compact('applications', 'stats'));
    }
}
