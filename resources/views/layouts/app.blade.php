<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'JobBot AI') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Tailwind Play CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- Alpine.js CDN -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script>
            // Theme detection script
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }

            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            navy: '#0a192f',
                        }
                    }
                }
            }
        </script>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 hidden md:flex flex-col sticky top-0 h-screen">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg">J</div>
                    <span class="font-black text-xl tracking-tight uppercase">JobBot <span class="text-blue-600">AI</span></span>
                </div>
                
                <nav class="flex-1 p-4 space-y-2 mt-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500' }} transition">
                        <span class="font-black text-[10px] uppercase tracking-widest">Dashboard</span>
                    </a>
                    
                    @if(Auth::user()->role === 'super_admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500' }} transition">
                        <span class="font-black text-[10px] uppercase tracking-widest">Admin Panel</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center p-3 rounded-xl {{ request()->routeIs('admin.settings.index') ? 'bg-blue-600 text-white shadow-lg' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500' }} transition">
                        <span class="font-black text-[10px] uppercase tracking-widest">Configurações</span>
                    </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="flex items-center p-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 transition">
                        <span class="font-black text-[10px] uppercase tracking-widest">Meu Perfil</span>
                    </a>
                </nav>

                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center p-3 rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition">
                            <span class="font-black text-[10px] uppercase tracking-widest">Sair do Sistema</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col h-screen overflow-y-auto">
                <!-- Top Header -->
                <header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 h-16 flex items-center justify-between px-8 sticky top-0 z-50">
                    <div class="flex items-center gap-4">
                        <h2 class="font-black text-[11px] uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">@yield('title', 'Dashboard')</h2>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Theme Toggle (No Icon) -->
                        <button onclick="toggleTheme()" class="px-4 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-blue-600 hover:text-white transition font-black text-[9px] uppercase tracking-widest">
                            Alternar Tema
                        </button>

                        <div class="w-px h-6 bg-gray-200 dark:bg-gray-700 mx-2"></div>

                        <div class="flex items-center gap-3">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ Auth::user()->name }}</p>
                                <p class="text-[9px] text-gray-400 font-black uppercase tracking-[0.1em]">{{ Auth::user()->is_premium ? 'Premium Member' : 'Free Tier' }}</p>
                            </div>
                            <img src="{{ Auth::user()->avatar_path ? asset('storage/'.Auth::user()->avatar_path) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=4f46e5&color=fff' }}" 
                                 class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-700 shadow-sm" alt="Avatar">
                        </div>
                    </div>
                </header>

                <main class="p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script>
            function toggleTheme() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
            }
        </script>
    </body>
</html>
