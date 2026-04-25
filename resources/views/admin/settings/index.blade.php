<x-app-layout>
    <div class="space-y-8 animate-in fade-in duration-700">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-[3rem] border border-gray-100 dark:border-gray-700">
            <div class="p-10 text-gray-900 dark:text-gray-100">
                
                <div class="mb-12">
                    <h2 class="text-3xl font-black tracking-tight uppercase leading-none">Configurações Globais</h2>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Gerenciamento de APIs e regras de negócio.</p>
                </div>

                @if(session('success'))
                    <div class="mb-8 p-6 bg-green-500/10 border border-green-500/20 text-green-500 rounded-2xl font-black text-xs uppercase tracking-widest">
                        Configurações atualizadas com sucesso
                    </div>
                @endif

                <form action="{{ route('admin.settings.store') }}" method="POST" class="space-y-10">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Gemini API -->
                        <div class="p-8 bg-gray-50 dark:bg-gray-900/50 rounded-[2rem] border border-gray-200 dark:border-gray-700">
                            <h3 class="text-xs font-black mb-6 uppercase tracking-widest text-blue-600">Google Gemini</h3>
                            <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Gemini API Key</label>
                            <input type="password" name="gemini_api_key" value="{{ $settings['gemini_api_key'] ?? '' }}" 
                                class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                        </div>

                        <!-- Evolution API -->
                        <div class="p-8 bg-gray-50 dark:bg-gray-900/50 rounded-[2rem] border border-gray-200 dark:border-gray-700">
                            <h3 class="text-xs font-black mb-6 uppercase tracking-widest text-green-600">WhatsApp (Evolution API)</h3>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Base URL</label>
                                    <input type="text" name="evolution_api_url" value="{{ $settings['evolution_api_url'] ?? '' }}" placeholder="https://api.seuservidor.com"
                                        class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Global API Key</label>
                                    <input type="password" name="evolution_api_key" value="{{ $settings['evolution_api_key'] ?? '' }}"
                                        class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Nome da Instância do Sistema</label>
                                    <input type="text" name="evolution_system_instance" value="{{ $settings['evolution_system_instance'] ?? 'jobbot_system' }}" placeholder="jobbot_system"
                                        class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                                    <p class="text-[10px] text-gray-400 mt-2 px-1">Nome da instância conectada no Evolution API (o número que dispara as mensagens para recrutadores e confirma envios para candidatos).</p>
                                </div>
                            </div>
                        </div>

                        <!-- AbacatePay -->
                        <div class="p-8 bg-gray-50 dark:bg-gray-900/50 rounded-[2rem] border border-gray-200 dark:border-gray-700">
                            <h3 class="text-xs font-black mb-6 uppercase tracking-widest text-amber-600">AbacatePay</h3>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">API Key</label>
                                    <input type="password" name="abacatepay_api_key" value="{{ $settings['abacatepay_api_key'] ?? '' }}" 
                                        class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Secret Key / Webhook</label>
                                    <input type="password" name="abacatepay_api_secret" value="{{ $settings['abacatepay_api_secret'] ?? '' }}" 
                                        class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition">
                                </div>
                            </div>
                        </div>

                        <!-- Pricing & Plans -->
                        <div class="p-8 bg-gray-50 dark:bg-gray-900/50 rounded-[2rem] border border-gray-200 dark:border-gray-700">
                            <h3 class="text-xs font-black mb-6 uppercase tracking-widest text-indigo-600">Precificação SaaS</h3>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Preço Premium (R$)</label>
                                    <input type="number" step="0.01" name="premium_price" value="{{ $settings['premium_price'] ?? '49.90' }}" 
                                        class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Créditos Iniciais</label>
                                    <input type="number" name="initial_credits" value="{{ $settings['initial_credits'] ?? '3' }}" 
                                        class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                                </div>
                            </div>
                        </div>

                        <!-- Serper.dev (Google Search) -->
                        <div class="p-8 bg-gray-50 dark:bg-gray-900/50 rounded-[2rem] border border-gray-200 dark:border-gray-700">
                            <h3 class="text-xs font-black mb-6 uppercase tracking-widest text-red-600">Serper.dev</h3>
                            <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Serper API Key</label>
                            <input type="password" name="serper_api_key" value="{{ $settings['serper_api_key'] ?? '' }}" 
                                class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                        </div>

                        <!-- Google Maps Places API -->
                        <div class="p-8 bg-gray-50 dark:bg-gray-900/50 rounded-[2rem] border border-gray-200 dark:border-gray-700">
                            <h3 class="text-xs font-black mb-2 uppercase tracking-widest text-green-700 dark:text-green-400">Google Maps (Places API)</h3>
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-5 leading-relaxed">Habilita a busca de empresas por cidade e setor diretamente no Google Maps, com telefone e site para contato direto.</p>
                            <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Google Maps API Key</label>
                            <input type="password" name="google_maps_api_key" value="{{ $settings['google_maps_api_key'] ?? '' }}"
                                class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
                            <p class="text-[9px] text-gray-400 mt-2 px-1">Ative as APIs: <strong>Places API</strong> e <strong>Maps JavaScript API</strong> no Google Cloud Console.</p>
                        </div>

                        <!-- JSearch (RapidAPI) -->
                        <div class="p-8 bg-gray-50 dark:bg-gray-900/50 rounded-[2rem] border border-gray-200 dark:border-gray-700">
                            <h3 class="text-xs font-black mb-6 uppercase tracking-widest text-indigo-600">JSearch (RapidAPI)</h3>
                            <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">RapidAPI Key</label>
                            <input type="password" name="jsearch_api_key" value="{{ $settings['jsearch_api_key'] ?? '' }}" 
                                class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>

                        <!-- Brevo (E-mails Transacionais) -->
                        <div class="p-8 bg-gray-50 dark:bg-gray-900/50 rounded-[2rem] border border-gray-200 dark:border-gray-700">
                            <h3 class="text-xs font-black mb-6 uppercase tracking-widest text-teal-600">Brevo (E-mails)</h3>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Brevo API Key</label>
                                    <input type="password" name="brevo_api_key" value="{{ $settings['brevo_api_key'] ?? '' }}"
                                        class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">E-mail Remetente (verificado no Brevo)</label>
                                    <input type="email" name="brevo_sender_email" value="{{ $settings['brevo_sender_email'] ?? '' }}" placeholder="noreply@seudominio.com"
                                        class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Nome Remetente</label>
                                    <input type="text" name="brevo_sender_name" value="{{ $settings['brevo_sender_name'] ?? 'JobBot AI' }}" placeholder="JobBot AI"
                                        class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl p-4 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition">
                                </div>
                            </div>
                        </div>


                        <!-- Legal Pages -->
                        <div class="p-8 bg-gray-50 dark:bg-gray-900/50 rounded-[2rem] border border-gray-100 dark:border-gray-700 col-span-1 md:col-span-2">
                            <h3 class="text-xs font-black mb-6 uppercase tracking-widest text-gray-500">Páginas Legais</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Privacidade</label>
                                    <textarea name="privacy_policy" rows="12" 
                                        class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-2xl p-6 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">{{ $settings['privacy_policy'] ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Termos</label>
                                    <textarea name="terms_of_service" rows="12" 
                                        class="w-full bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-2xl p-6 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">{{ $settings['terms_of_service'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-10 border-t border-gray-100 dark:border-gray-700">
                        <button type="submit" class="bg-blue-600 text-white font-black py-5 px-12 rounded-[1.5rem] shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition transform hover:-translate-y-1 uppercase text-xs tracking-[0.2em]">
                            Salvar Configurações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
