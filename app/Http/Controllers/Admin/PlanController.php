<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = \App\Models\Plan::all();
        return view('admin.plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'credits' => 'required|integer',
        ]);

        \App\Models\Plan::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price * 100, // Converte para centavos
            'credits' => $request->credits,
            'external_id' => $request->external_id,
        ]);

        return redirect()->back()->with('success', 'Plano criado com sucesso!');
    }
}
