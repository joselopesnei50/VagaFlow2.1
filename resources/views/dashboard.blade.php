<x-app-layout>
    @section('title', 'Central do Candidato')

    <div class="space-y-8 animate-in fade-in duration-700" x-data="prospectingFlow()">
        
        @if(auth()->user()->role === 'user' && (!auth()->user()->profile || !auth()->user()->profile->cv_path))
            <!-- WIZARD / ONBOARDING FORM -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-white dark:bg-gray-800 rounded-[3rem] shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-10 text-white relative overflow-hidden text-center md:text-left">
                        <div class="relative z-10">
                            <h3 class="text-3xl font-black mb-2 tracking-tight uppercase">Primeiros Passos</h3>
                            <p class="text-blue-100 font-medium opacity-90">Ainda não detectamos um currículo. Preencha os dados abaixo para que nossa IA possa trabalhar por você.</p>
                        </div>
                    </div>
                    <form action="{{ route('cv.upload') }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <h4 class="font-black text-gray-900 dark:text-white flex items-center gap-3 uppercase text-xs tracking-widest border-b border-gray-100 dark:border-gray-700 pb-2">Objetivo Profissional</h4>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Cargo Alvo</label>
                                    <input type="text" name="target_role" required class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 transition shadow-inner dark:text-white text-gray-900" placeholder="Ex: Desenvolvedor Fullstack">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Seu WhatsApp</label>
                                    <input type="text" name="whatsapp_number" required class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 transition shadow-inner dark:text-white text-gray-900" placeholder="5511999999999">
                                </div>
                            </div>
                            <div class="space-y-4">
                                <h4 class="font-black text-gray-900 dark:text-white flex items-center gap-3 uppercase text-xs tracking-widest border-b border-gray-100 dark:border-gray-700 pb-2">Sua Trajetória</h4>
                                <textarea name="bio" rows="5" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 transition shadow-inner dark:text-white text-gray-900" placeholder="Conte-nos brevemente sobre sua experiência..."></textarea>
                                
                                <h4 class="font-black text-gray-900 dark:text-white flex items-center gap-3 uppercase text-xs tracking-widest border-b border-gray-100 dark:border-gray-700 pb-2 mt-6">Controle da IA</h4>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2 px-1">Limite Máximo de Disparos Automáticos</label>
                                    <input type="number" name="auto_limit" value="5" min="1" max="30" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 transition shadow-inner dark:text-white text-gray-900">
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4 pt-4 border-t border-gray-50 dark:border-gray-700">
                            <h4 class="font-black text-gray-900 dark:text-white flex items-center gap-3 uppercase text-xs tracking-widest">Arquivo de Currículo</h4>
                            <div class="relative group">
                                <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2rem] blur opacity-10 transition duration-1000"></div>
                                <div class="relative flex flex-col items-center justify-center border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-[2rem] p-10 bg-white dark:bg-gray-800 hover:border-blue-500 transition-all cursor-pointer">
                                    <input type="file" name="cv" class="absolute inset-0 opacity-0 cursor-pointer">
                                    <p class="text-sm font-black text-gray-600 dark:text-gray-300 uppercase tracking-widest">Carregar Arquivo</p>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-gray-900 dark:bg-white dark:text-gray-900 text-white font-black py-5 rounded-[2rem] hover:bg-blue-600 dark:hover:bg-blue-600 dark:hover:text-white transition-all transform hover:-translate-y-1 uppercase text-xs tracking-widest shadow-xl">
                            Finalizar e Ativar Sistema
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- MAIN DASHBOARD -->
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-blue-500/20 mb-6">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div>
                        <span class="px-4 py-1.5 bg-white/20 border border-white/30 rounded-full text-[9px] font-black uppercase tracking-widest mb-4 inline-block">Prospecção Ativa</span>
                        <h3 class="text-3xl font-black mb-2 tracking-tight uppercase leading-none">Olá, {{ explode(' ', Auth::user()->name)[0] }}!</h3>
                        <p class="text-blue-100 font-medium opacity-90 max-w-lg">Cargo alvo: <span class="font-black text-white">{{ auth()->user()->profile->target_role ?? 'Não definido' }}</span></p>
                    </div>
                    <div class="flex flex-wrap justify-center gap-3">
                        <button @click="fetchJobs()" class="px-8 py-4 bg-white text-blue-700 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:shadow-2xl transition hover:scale-105">
                            Buscar Vagas (Job Boards)
                        </button>
                        <button @click="openMapsSearch()" class="px-8 py-4 bg-green-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-green-400 transition hover:scale-105">
                            Buscar Empresas (Maps)
                        </button>
                        <button @click="startAutopilot()" class="px-8 py-4 bg-white/10 border border-white/30 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-white/20 transition">
                            Piloto Automático
                        </button>
                    </div>
                </div>
                <div class="absolute top-[-20%] right-[-10%] w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            </div>

            <!-- MAPS SEARCH FORM -->
            <div x-show="showMapsForm" x-transition class="mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <h3 class="font-black text-sm uppercase tracking-widest text-gray-800 dark:text-white mb-4">Buscar Empresas pelo Google Maps</h3>
                    <div class="flex gap-3 flex-wrap">
                        <input type="text" x-model="mapsCity" placeholder="Cidade (ex: São Paulo)"
                               class="flex-1 min-w-[160px] bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none">
                        <button @click="fetchByMaps()" class="px-6 py-3 bg-green-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-green-700 transition">
                            Buscar
                        </button>
                        <button @click="showMapsForm = false" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>

            <!-- JOB / COMPANY RESULTS -->
            <div x-show="jobs.length > 0" x-transition class="mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-8 shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="font-black text-lg uppercase tracking-tight text-gray-900 dark:text-white" x-text="jobs[0]?.via === 'Google Maps' ? 'Empresas Encontradas — Google Maps' : 'Vagas Encontradas'"></h3>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mt-1" x-text="jobs.length + ' resultado(s)'"></p>
                        </div>
                        <button @click="jobs = []" class="text-[10px] font-black text-gray-500 hover:text-red-500 uppercase tracking-widest transition">Limpar</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        <template x-for="job in jobs" :key="job.job_id">
                            <div class="group p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-blue-500 hover:shadow-md transition-all duration-300">
                                <div class="flex items-center gap-3 mb-4">
                                    <img :src="job.thumbnail" class="w-11 h-11 rounded-xl object-cover bg-gray-200" alt="Logo"
                                         onerror="this.src='https://ui-avatars.com/api/?name='+encodeURIComponent(job.company_name)+'&background=e2e8f0&color=475569'">
                                    <div class="min-w-0">
                                        <h4 class="font-black text-xs text-gray-900 dark:text-white uppercase leading-tight tracking-tight truncate" x-text="job.title"></h4>
                                        <p class="text-[10px] font-bold text-gray-600 dark:text-gray-400 uppercase tracking-wide mt-0.5 truncate" x-text="job.company_name"></p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 mb-4 leading-relaxed" x-text="job.description"></p>
                                <div class="flex flex-wrap gap-2 mb-5">
                                    <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 rounded-lg text-[9px] font-black uppercase tracking-wide text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700" x-text="job.location"></span>
                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wide border"
                                          :class="job.via === 'Google Maps'
                                              ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800'
                                              : 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/20 dark:text-blue-400 dark:border-blue-800'"
                                          x-text="'via ' + job.via"></span>
                                    <template x-if="job.contact_phone">
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-400 dark:border-emerald-800 rounded-lg text-[9px] font-black uppercase tracking-wide">📞 Tel. Encontrado</span>
                                    </template>
                                </div>
                                <button @click="startAnalysis(job)"
                                        class="w-full py-3.5 bg-gray-900 dark:bg-white dark:text-gray-900 text-white font-black rounded-xl text-[10px] uppercase tracking-widest group-hover:bg-blue-600 group-hover:dark:bg-blue-600 group-hover:dark:text-white group-hover:text-white transition-all">
                                    Analisar & Disparar
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- STATS CARDS -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <span class="text-[9px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">Total</span>
                    <p class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mt-3 mb-1">Candidaturas</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">{{ $stats['total'] }}</h3>
                    <div class="h-10 mt-3"><canvas id="sparklineSent"></canvas></div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <span class="text-[9px] font-black uppercase tracking-widest text-green-600 dark:text-green-400">Sucesso</span>
                    <p class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mt-3 mb-1">Enviadas</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">{{ $stats['sent'] }}</h3>
                    <div class="h-10 mt-3"><canvas id="sparklineDelivered"></canvas></div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <span class="text-[9px] font-black uppercase tracking-widest text-amber-600 dark:text-amber-400">Créditos</span>
                    <p class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mt-3 mb-1">Disponíveis</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">{{ auth()->user()->credits ?? 0 }}</h3>
                    <div class="h-10 mt-3"><canvas id="sparklineRead"></canvas></div>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <span class="text-[9px] font-black uppercase tracking-widest text-purple-600 dark:text-purple-400">Respostas</span>
                    <p class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mt-3 mb-1">Retornos</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">0</h3>
                    <div class="h-10 mt-3"><canvas id="sparklineResponses"></canvas></div>
                </div>
            </div>

            {{-- TABELA DE CANDIDATURAS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-black text-base uppercase tracking-tight text-gray-900 dark:text-white">Rastreamento de Candidaturas</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-600 dark:text-gray-400">Empresa</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-600 dark:text-gray-400">Data</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-600 dark:text-gray-400 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($applications as $app)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-700 dark:text-blue-300 font-black text-sm shrink-0">
                                                {{ strtoupper(substr($app->company_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-black text-sm text-gray-900 dark:text-white uppercase tracking-wide">{{ $app->company_name }}</p>
                                                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                                    <span class="text-[9px] font-black px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 rounded-md">{{ $app->match_score ?? 0 }}% match</span>
                                                    <span class="text-[10px] text-gray-500 dark:text-gray-400 font-medium italic truncate max-w-xs">{{ $app->strategy_note ?? '' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $app->created_at->format('d/m/Y') }}</p>
                                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold mt-0.5">{{ $app->created_at->format('H:i') }}</p>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        @php
                                            $statusMap = [
                                                'sent'      => ['bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300', 'Enviado'],
                                                'delivered' => ['bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', 'Entregue'],
                                                'read'      => ['bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400', 'Lido'],
                                                'pending'   => ['bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'Pendente'],
                                            ];
                                            [$sc, $sl] = $statusMap[$app->status] ?? $statusMap['sent'];
                                        @endphp
                                        <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-wide {{ $sc }}">{{ $sl }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-16 text-center">
                                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Nenhuma candidatura ainda.</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Busque vagas e dispare sua primeira candidatura!</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- SIDEBAR DIREITA --}}
                <div class="space-y-5">
                    {{-- Dica da IA --}}
                    <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 text-white shadow-lg shadow-indigo-500/20">
                        <p class="text-[9px] font-black uppercase tracking-widest text-indigo-200 mb-3">Dica da IA</p>
                        <p class="text-sm font-medium leading-relaxed opacity-90">"Combine a busca por Job Boards com o Google Maps para maximizar suas chances. Empresas locais costumam responder mais rápido."</p>
                    </div>

                    {{-- Status das APIs --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                        <h3 class="font-black text-xs uppercase tracking-widest text-gray-700 dark:text-gray-300 mb-4">Canais Ativos</h3>
                        <div class="space-y-3">
                            @foreach([
                                ['Job Boards', 'JSearch / Serper', 'bg-blue-500'],
                                ['Google Maps', 'Places API', 'bg-green-500'],
                                ['WhatsApp', 'Evolution API', 'bg-emerald-500'],
                                ['E-mail', 'Brevo', 'bg-teal-500'],
                                ['IA', 'Gemini 1.5 Flash', 'bg-purple-500'],
                            ] as [$canal, $tech, $color])
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $color }}"></span>
                                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $canal }}</span>
                                </div>
                                <span class="text-[9px] font-bold text-gray-500 dark:text-gray-400">{{ $tech }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Upgrade se free --}}
                    @if(!auth()->user()->is_premium)
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-6">
                        <p class="text-xs font-black uppercase tracking-widest text-amber-700 dark:text-amber-400 mb-2">Aumente seus Resultados</p>
                        <p class="text-sm text-amber-800 dark:text-amber-300 font-medium mb-4">Faça upgrade para Premium e ative o Piloto Automático sem limites.</p>
                        <a href="{{ route('checkout') }}" class="block text-center py-3 bg-amber-500 hover:bg-amber-600 text-white font-black text-[10px] uppercase tracking-widest rounded-xl transition">
                            Ver Planos Premium
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- ── MODAL DE PREVIEW ────────────────────────────────────────────── --}}
        <template x-if="showPreview">
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden" @click.away="showPreview = false">
                    <div class="bg-gray-900 px-8 py-6 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-black uppercase tracking-tight text-white">Análise de Prospecção</h3>
                            <p class="text-[10px] text-gray-400 mt-0.5 uppercase tracking-widest" x-text="previewData.company_name"></p>
                        </div>
                        <span class="px-4 py-2 bg-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest text-white" x-text="previewData.match + '% Match'"></span>
                    </div>
                    <div class="p-8 space-y-5">
                        <div>
                            <p class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2">Estratégia da IA</p>
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                                <p class="text-sm font-bold text-blue-800 dark:text-blue-300 italic" x-text="'&quot;' + previewData.strategy + '&quot;'"></p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-2">Pitch para WhatsApp do RH</p>
                            <div class="p-5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl">
                                <p class="text-sm leading-relaxed text-gray-800 dark:text-gray-200 font-medium" x-text="previewData.pitch"></p>
                            </div>
                        </div>
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
                            <p class="text-[11px] font-bold text-green-800 dark:text-green-300 leading-relaxed">
                                🚀 A IA vai localizar o e-mail e WhatsApp do RH e disparar sua candidatura diretamente para a empresa. Você receberá uma confirmação no seu WhatsApp.
                            </p>
                        </div>
                        <div class="flex gap-3 pt-1">
                            <button @click="showPreview = false"
                                    class="flex-1 py-4 rounded-2xl border border-gray-300 dark:border-gray-600 text-sm font-black uppercase tracking-widest text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                Cancelar
                            </button>
                            <button @click="confirmSend()"
                                    class="flex-[2] py-4 bg-blue-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 disabled:opacity-60"
                                    :disabled="sending">
                                <span x-show="!sending">Disparar para Empresa</span>
                                <span x-show="sending">Enviando...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- LOADING --}}
        <template x-if="loading">
            <div class="fixed inset-0 z-[110] flex flex-col items-center justify-center bg-white/90 dark:bg-gray-950/90 backdrop-blur-md">
                <div class="w-14 h-14 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-5"></div>
                <p class="text-xs font-black uppercase tracking-widest text-gray-600 dark:text-gray-300 animate-pulse" x-text="loadingMessage"></p>
            </div>
        </template>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function prospectingFlow() {
            return {
                loading:        false,
                loadingMessage: 'IA Analisando...',
                showPreview:    false,
                showMapsForm:   false,
                sending:        false,
                jobs:           [],
                mapsCity:       '',
                previewData:    { pitch: '', strategy: '', match: 0, company_name: '' },

                openMapsSearch() {
                    this.showMapsForm = !this.showMapsForm;
                    this.jobs = [];
                },

                async fetchJobs() {
                    this.showMapsForm = false;
                    this.loading = true;
                    this.loadingMessage = 'Buscando vagas em Job Boards...';
                    try {
                        const res  = await fetch("{{ route('cv.search') }}");
                        this.jobs  = await res.json();
                    } catch (e) {
                        alert('Erro ao buscar vagas. Verifique as API keys nas configurações.');
                    } finally {
                        this.loading = false;
                    }
                },

                async fetchByMaps() {
                    if (!this.mapsCity.trim()) { alert('Informe uma cidade.'); return; }
                    this.loading = true;
                    this.loadingMessage = 'Buscando empresas no Google Maps...';
                    try {
                        const url  = `{{ route('cv.search.maps') }}?city=${encodeURIComponent(this.mapsCity)}`;
                        const res  = await fetch(url);
                        const data = await res.json();
                        if (!data.length) { alert('Nenhuma empresa encontrada. Verifique a chave do Google Maps nas configurações.'); return; }
                        this.jobs         = data;
                        this.showMapsForm = false;
                    } catch (e) {
                        alert('Erro ao buscar no Google Maps.');
                    } finally {
                        this.loading = false;
                    }
                },

                async startAutopilot() {
                    if (!confirm('O Piloto Automático vai buscar vagas e disparar candidaturas em segundo plano. Confirma?')) return;
                    this.loading = true;
                    this.loadingMessage = 'Ativando Piloto Automático...';
                    try {
                        const res  = await fetch("{{ route('cv.autopilot') }}", {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                        });
                        const data = await res.json();
                        if (data.error) throw new Error(data.error);
                        alert('Piloto Automático ativado! Você receberá as confirmações no WhatsApp.');
                    } catch (e) {
                        alert(e.message || 'Erro ao ativar piloto automático.');
                    } finally {
                        this.loading = false;
                    }
                },

                async startAnalysis(job) {
                    this.loading = true;
                    this.loadingMessage = 'IA analisando ' + job.company_name + '...';
                    try {
                        const res  = await fetch("{{ route('cv.analyze') }}", {
                            method:  'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body:    JSON.stringify({
                                company_name:  job.company_name,
                                title:         job.title,
                                description:   job.description,
                                location:      job.location,
                                via:           job.via,
                                job_url:       job.job_url       ?? null,
                                contact_phone: job.contact_phone ?? null,
                                contact_email: job.contact_email ?? null,
                            })
                        });
                        const data = await res.json();
                        if (data.error) throw new Error(data.error);
                        this.previewData = { ...data, ...job };
                        this.showPreview = true;
                    } catch (e) {
                        alert(e.message || 'Erro ao analisar vaga.');
                    } finally {
                        this.loading = false;
                    }
                },

                async confirmSend() {
                    this.sending = true;
                    try {
                        const res  = await fetch("{{ route('cv.send') }}", {
                            method:  'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body:    JSON.stringify(this.previewData)
                        });
                        const data = await res.json();
                        if (data.error) throw new Error(data.error);
                        window.location.reload();
                    } catch (e) {
                        alert(e.message || 'Erro ao enviar candidatura.');
                    } finally {
                        this.sending = false;
                    }
                }
            }
        }

        // Sparklines
        const sparkOpts = c => ({
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales:  { x: { display: false }, y: { display: false } },
            elements: { point: { radius: 0 }, line: { tension: 0.4, borderWidth: 2.5, borderColor: c } }
        });
        new Chart(document.getElementById('sparklineSent'),      { type: 'line', data: { labels: [1,2,3,4,5,6], datasets: [{ data: [3,8,5,14,10,18] }] }, options: sparkOpts('#3b82f6') });
        new Chart(document.getElementById('sparklineDelivered'), { type: 'line', data: { labels: [1,2,3,4,5,6], datasets: [{ data: [2,5,8,6,11,14] }] }, options: sparkOpts('#10b981') });
        new Chart(document.getElementById('sparklineRead'),      { type: 'line', data: { labels: [1,2,3,4,5,6], datasets: [{ data: [0,2,3,4,6,5]  }] }, options: sparkOpts('#f59e0b') });
        new Chart(document.getElementById('sparklineResponses'), { type: 'line', data: { labels: [1,2,3,4,5,6], datasets: [{ data: [0,1,2,1,3,2]  }] }, options: sparkOpts('#a855f7') });
    </script>
</x-app-layout>
