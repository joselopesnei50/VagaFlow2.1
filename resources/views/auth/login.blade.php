<x-guest-layout>
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left Side: Branding & Visuals -->
        <div class="md:w-1/2 bg-[#050505] p-12 md:p-24 flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <a href="/" class="flex items-center gap-3 mb-12">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg">J</div>
                    <span class="font-black text-2xl tracking-tight text-white uppercase">JobBot <span class="text-blue-600">AI</span></span>
                </a>

                <div class="space-y-6">
                    <h1 class="text-6xl md:text-8xl font-black text-white leading-none tracking-tighter uppercase">
                        Let's <br> <span class="text-blue-600">Apply.</span>
                    </h1>
                    <p class="text-gray-400 text-lg md:text-xl font-medium max-w-sm">
                        Deixe nossa inteligência artificial cuidar da sua carreira enquanto você foca no que importa.
                    </p>
                </div>
            </div>

            <div class="relative z-10 grid grid-cols-2 gap-8 pt-12 border-t border-white/10 uppercase tracking-widest">
                <div>
                    <h4 class="text-white font-black text-[10px] mb-2">Resposta Rápida</h4>
                    <p class="text-gray-500 text-[9px] leading-relaxed">Geração automática de abordagens personalizadas.</p>
                </div>
                <div>
                    <h4 class="text-white font-black text-[10px] mb-2">Passos Claros</h4>
                    <p class="text-gray-500 text-[9px] leading-relaxed">Monitoramento de visualização em tempo real.</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="md:w-1/2 bg-white flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <div class="text-center mb-10">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight uppercase leading-none">Bem-vindo</h2>
                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mt-4">Acesse sua conta para gerenciar disparos.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="email">E-mail</label>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition shadow-inner"
                            placeholder="seu@email.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="password">Senha</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition shadow-inner"
                            placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                            <span class="ml-2 text-[10px] text-gray-400 font-black uppercase tracking-widest">Lembrar</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-[10px] text-blue-600 font-black uppercase tracking-widest hover:underline" href="{{ route('password.request') }}">
                                Esqueci a senha
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-[#050505] text-white font-black py-5 rounded-[1.5rem] hover:bg-blue-600 transition-all shadow-xl uppercase text-xs tracking-[0.2em]">
                        Entrar no Console
                    </button>

                    <div class="text-center pt-8 border-t border-gray-50 mt-4">
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Não tem uma conta? 
                            <a href="{{ route('register') }}" class="text-blue-600 hover:underline ml-1">Criar agora</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
