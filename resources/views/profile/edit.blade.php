<x-app-layout>
    @section('title', 'Configurações de Perfil')

    <div class="max-w-5xl mx-auto space-y-8 animate-in fade-in duration-700 pb-20">
        
        <!-- Profile Header / Avatar Section (Icons removed) -->
        <div class="bg-gradient-to-br from-indigo-600 via-blue-700 to-purple-800 rounded-[3rem] p-10 text-white shadow-2xl shadow-indigo-500/20 relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full blur opacity-40 transition duration-1000"></div>
                    <img src="{{ Auth::user()->avatar_path ? asset('storage/'.Auth::user()->avatar_path) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&size=200&background=4f46e5&color=fff' }}" 
                         class="relative w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white shadow-2xl object-cover" alt="Avatar">
                    <button class="absolute bottom-2 right-2 px-4 py-2 bg-white text-gray-900 rounded-xl shadow-xl hover:bg-blue-600 hover:text-white transition-all transform hover:scale-105 font-black text-[9px] uppercase tracking-widest">
                        Alterar
                    </button>
                </div>
                <div class="text-center md:text-left">
                    <h3 class="text-3xl font-black tracking-tight uppercase leading-none">{{ Auth::user()->name }}</h3>
                    <p class="text-indigo-100 font-black text-[10px] uppercase tracking-widest opacity-80 mt-2">{{ Auth::user()->email }}</p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-3 mt-8">
                        <span class="px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl text-[9px] font-black uppercase tracking-widest">{{ Auth::user()->is_premium ? 'Assinante Pro' : 'Membro Gratuito' }}</span>
                        <span class="px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl text-[9px] font-black uppercase tracking-widest">Ativo {{ Auth::user()->created_at->diffInDays() }} dias</span>
                    </div>
                </div>
            </div>
            <!-- Abstract background elements -->
            <div class="absolute top-[-20%] right-[-10%] w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Navigation Side (Icons removed) -->
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white dark:bg-gray-800 p-10 rounded-[3rem] shadow-sm border border-gray-100 dark:border-gray-700">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-8">Navegação</h4>
                    <nav class="space-y-3">
                        <a href="#personal" class="flex items-center justify-between p-5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-2xl border border-blue-100 dark:border-blue-800/50 transition">
                            <span class="text-[10px] font-black uppercase tracking-widest">Dados Pessoais</span>
                        </a>
                        <a href="#security" class="flex items-center justify-between p-5 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-2xl transition group text-gray-500">
                            <span class="text-[10px] font-black uppercase tracking-widest group-hover:text-gray-900 dark:group-hover:text-white transition">Segurança</span>
                        </a>
                        <a href="#danger" class="flex items-center justify-between p-5 hover:bg-red-50 dark:hover:bg-red-900/10 rounded-2xl transition group text-red-400">
                            <span class="text-[10px] font-black uppercase tracking-widest transition">Encerrar Conta</span>
                        </a>
                    </nav>
                </div>

                <div class="bg-gray-900 rounded-[3rem] p-10 text-white shadow-xl relative overflow-hidden">
                    <h3 class="text-xl font-black mb-2 uppercase tracking-tighter">Plano Atual</h3>
                    <p class="text-gray-600 text-[10px] font-black uppercase tracking-widest mb-8">Status: Básico</p>
                    <a href="{{ route('checkout') }}" class="block w-full bg-blue-600 py-5 rounded-[1.5rem] text-center font-black text-[10px] uppercase tracking-[0.2em] hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">Upgrade</a>
                </div>
            </div>

            <!-- Forms Side -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Personal Info -->
                <section id="personal" class="bg-white dark:bg-gray-800 p-12 rounded-[3rem] shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="mb-12">
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight uppercase leading-none">Informações</h3>
                        <p class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mt-2">Dados básicos e cargo alvo.</p>
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </section>

                <!-- Security Info -->
                <section id="security" class="bg-white dark:bg-gray-800 p-12 rounded-[3rem] shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="mb-12">
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight uppercase leading-none">Segurança</h3>
                        <p class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mt-2">Proteção da conta.</p>
                    </div>
                    @include('profile.partials.update-password-form')
                </section>

                <!-- Danger Zone -->
                <section id="danger" class="bg-red-50/30 dark:bg-red-900/10 p-12 rounded-[3rem] border border-red-100 dark:border-red-900/30">
                    <div class="mb-12">
                        <h3 class="text-2xl font-black text-red-600 tracking-tight uppercase leading-none">Zona de Perigo</h3>
                        <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mt-2">Ações irreversíveis.</p>
                    </div>
                    @include('profile.partials.delete-user-form')
                </section>
            </div>
        </div>
    </div>

    <style>
        .animate-in {
            animation: fadeIn 0.8s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        html {
            scroll-behavior: smooth;
        }
    </style>
</x-app-layout>
