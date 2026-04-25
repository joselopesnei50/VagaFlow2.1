<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'JobBot AI') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: { sans: ['Inter', 'sans-serif'] },
                    }
                }
            }
        </script>

        <style>
            /* ── Modo Claro: garantir contraste em labels, inputs e bordas ── */
            .field-label {
                @apply block text-[10px] font-black text-gray-600 uppercase tracking-widest mb-2 px-1;
            }
            .card {
                @apply bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-200 dark:border-gray-700 shadow-sm;
            }
            .card-inner {
                @apply bg-gray-100 dark:bg-gray-900/60 rounded-[1.5rem] border border-gray-200 dark:border-gray-700;
            }
            .input-base {
                @apply w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600
                       rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition;
            }
            .input-password { @apply input-base; }
            .stat-label {
                @apply text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-1;
            }
            .stat-value {
                @apply text-3xl font-black text-gray-900 dark:text-white tracking-tighter;
            }
            .section-title {
                @apply text-xl font-black text-gray-900 dark:text-white tracking-tight uppercase;
            }
            .tag {
                @apply text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-lg;
            }
            .animate-in {
                animation: fadeIn 0.6s ease-out forwards;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(16px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            /* Scrollbar */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
            .dark ::-webkit-scrollbar-thumb { background: #374151; }
        </style>
    </head>

    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <div class="min-h-screen flex">

            {{-- ── SIDEBAR ─────────────────────────────────────────────── --}}
            <aside class="w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 hidden md:flex flex-col sticky top-0 h-screen shrink-0">
                {{-- Logo --}}
                <div class="p-6 border-b border-gray-200 dark:border-gray-800 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-blue-500/30">J</div>
                    <span class="font-black text-lg tracking-tight text-gray-900 dark:text-white">JobBot <span class="text-blue-600">AI</span></span>
                </div>

                {{-- Créditos do usuário --}}
                @if(Auth::user()->role !== 'super_admin')
                <div class="mx-4 mt-4 p-4 rounded-2xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                    <p class="text-[9px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400 mb-1">Créditos Disponíveis</p>
                    <p class="text-2xl font-black text-blue-700 dark:text-blue-300">{{ Auth::user()->credits ?? 0 }}</p>
                    @if(!Auth::user()->is_premium)
                        <a href="{{ route('checkout') }}" class="mt-2 block text-center text-[9px] font-black uppercase tracking-widest text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 transition">
                            Upgrade Premium →
                        </a>
                    @else
                        <span class="inline-block mt-1 text-[9px] font-black text-green-600 dark:text-green-400 uppercase tracking-widest">✓ Premium Ativo</span>
                    @endif
                </div>
                @endif

                {{-- Navegação --}}
                <nav class="flex-1 p-4 space-y-1 mt-3">
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 p-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition
                              {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>

                    @if(Auth::user()->role === 'super_admin')
                    <div class="pt-3 pb-1">
                        <p class="px-3 text-[8px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Administração</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 p-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition
                              {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Painel Admin
                    </a>
                    <a href="{{ route('admin.settings.index') }}"
                       class="flex items-center gap-3 p-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition
                              {{ request()->routeIs('admin.settings*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                        Configurações
                    </a>
                    <a href="{{ route('admin.posts.index') }}"
                       class="flex items-center gap-3 p-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition
                              {{ request()->routeIs('admin.posts*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Blog / CMS
                    </a>
                    <a href="{{ route('admin.plans.index') }}"
                       class="flex items-center gap-3 p-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition
                              {{ request()->routeIs('admin.plans*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Planos
                    </a>
                    @endif

                    <div class="pt-3 pb-1">
                        <p class="px-3 text-[8px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Conta</p>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 p-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition
                              {{ request()->routeIs('profile*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Meu Perfil
                    </a>
                </nav>

                {{-- Logout --}}
                <div class="p-4 border-t border-gray-200 dark:border-gray-800">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-xl text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition font-black text-[10px] uppercase tracking-widest">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Sair do Sistema
                        </button>
                    </form>
                </div>
            </aside>

            {{-- ── CONTEÚDO PRINCIPAL ──────────────────────────────────── --}}
            <div class="flex-1 flex flex-col h-screen overflow-y-auto min-w-0">

                {{-- Header --}}
                <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 h-16 flex items-center justify-between px-8 sticky top-0 z-50 shadow-sm">
                    <h2 class="font-black text-sm uppercase tracking-widest text-gray-700 dark:text-gray-300">
                        @yield('title', 'Dashboard')
                    </h2>

                    <div class="flex items-center gap-3">
                        {{-- Toggle Tema --}}
                        <button onclick="toggleTheme()"
                                class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 hover:text-blue-600 transition"
                                title="Alternar tema">
                            <svg class="w-4 h-4 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                            <svg class="w-4 h-4 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </button>

                        <div class="w-px h-6 bg-gray-200 dark:bg-gray-700"></div>

                        {{-- User info --}}
                        <div class="flex items-center gap-3">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ Auth::user()->name }}</p>
                                <p class="text-[9px] font-bold uppercase tracking-widest {{ Auth::user()->is_premium ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                                    {{ Auth::user()->is_premium ? '★ Premium' : 'Plano Gratuito' }}
                                </p>
                            </div>
                            <img src="{{ Auth::user()->avatar_path ? asset('storage/'.Auth::user()->avatar_path) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=2563eb&color=fff&bold=true' }}"
                                 class="w-9 h-9 rounded-full border-2 border-gray-200 dark:border-gray-700 shadow-sm" alt="Avatar">
                        </div>
                    </div>
                </header>

                <main class="p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script>
            function toggleTheme() {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.theme = isDark ? 'dark' : 'light';
            }
        </script>
    </body>
</html>
