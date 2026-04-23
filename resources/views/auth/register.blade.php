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
                        Join the <br> <span class="text-blue-600">Elite.</span>
                    </h1>
                    <p class="text-gray-400 text-lg md:text-xl font-medium max-w-sm">
                        Cadastre-se hoje e deixe que os melhores empregos venham até você de forma automatizada.
                    </p>
                </div>
            </div>

            <div class="relative z-10 grid grid-cols-2 gap-8 pt-12 border-t border-white/10 uppercase tracking-widest">
                <div>
                    <h4 class="text-white font-black text-[10px] mb-2">Segurança Total</h4>
                    <p class="text-gray-500 text-[9px] leading-relaxed">Dados processados de forma privada.</p>
                </div>
                <div>
                    <h4 class="text-white font-black text-[10px] mb-2">Ativação Rápida</h4>
                    <p class="text-gray-500 text-[9px] leading-relaxed">Configuração em menos de 2 minutos.</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Register Form -->
        <div class="md:w-1/2 bg-white flex items-center justify-center p-8 overflow-y-auto">
            <div class="w-full max-w-md py-12">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight uppercase leading-none">Criar Conta</h2>
                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mt-4">Junte-se a milhares de candidatos.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="name">Nome Completo</label>
                        <input id="name" type="text" name="name" :value="old('name')" required autofocus 
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition shadow-inner"
                            placeholder="Seu nome">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="email">E-mail</label>
                        <input id="email" type="email" name="email" :value="old('email')" required 
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition shadow-inner"
                            placeholder="seu@email.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="password">Senha</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition shadow-inner"
                            placeholder="••••••••">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="password_confirmation">Confirmar</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required 
                            class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition shadow-inner"
                            placeholder="Repita a senha">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <button type="submit" class="w-full bg-[#050505] text-white font-black py-5 rounded-[1.5rem] hover:bg-blue-600 transition-all shadow-xl uppercase text-xs tracking-[0.2em] mt-4">
                        Começar Agora
                    </button>

                    <div class="text-center pt-8 border-t border-gray-50 mt-6">
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Já possui uma conta? 
                            <a href="{{ route('login') }}" class="text-blue-600 hover:underline ml-1">Fazer login</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
