<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Plan;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Admin padrão — troque a senha após o primeiro login!
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@jobbot.ai')],
            [
                'name'              => 'Admin',
                'password'          => Hash::make(env('ADMIN_PASSWORD', 'Admin@12345')),
                'role'              => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        // Planos padrão (price em centavos)
        $plans = [
            ['name' => 'Starter',      'price' => 2990, 'credits' => 10, 'is_active' => true],
            ['name' => 'Profissional', 'price' => 4990, 'credits' => 30, 'is_active' => true],
            ['name' => 'Premium',      'price' => 8990, 'credits' => 80, 'is_active' => true],
        ];

        foreach ($plans as $plan) {
            Plan::firstOrCreate(['name' => $plan['name']], $plan);
        }
    }
}
