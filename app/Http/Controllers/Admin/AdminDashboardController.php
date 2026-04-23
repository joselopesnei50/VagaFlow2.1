<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => \App\Models\User::where('role', 'user')->count(),
            'premium_users' => \App\Models\User::where('is_premium', true)->count(),
            'total_revenue' => \App\Models\Payment::where('status', 'paid')->sum('amount'),
            'total_applications' => \App\Models\Application::count(),
            'total_today' => \App\Models\Application::whereDate('created_at', \Carbon\Carbon::today())->count(),
            'recent_users' => \App\Models\User::where('role', 'user')->latest()->take(5)->get(),
            'recent_payments' => \App\Models\Payment::with('user')->latest()->take(5)->get(),
            'recent_logs' => \App\Models\SearchLog::with('user')->latest()->take(10)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
