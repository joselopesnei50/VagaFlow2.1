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
            <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-blue-500/20 mb-8">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div>
                        <span class="px-4 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-[9px] font-black uppercase tracking-widest mb-4 inline-block">Prospecção Ativa</span>
                        <h3 class="text-3xl font-black mb-2 tracking-tight uppercase leading-none">Olá, {{ explode(' ', Auth::user()->name)[0] }}!</h3>
                        <p class="text-blue-100 font-medium opacity-90 max-w-lg">Buscando por: <span class="font-black text-white underline">{{ auth()->user()->profile->target_role ?? 'Cargos de TI' }}</span></p>
                    </div>
                    <div class="flex flex-wrap justify-center gap-4">
                        <button @click="fetchJobs()" class="px-10 py-5 bg-white text-blue-600 rounded-[1.5rem] font-black text-[10px] uppercase tracking-widest hover:shadow-2xl transition transform hover:scale-105">
                            Buscar Vagas Reais
                        </button>
                        <button @click="startAutopilot()" class="px-10 py-5 bg-blue-500/20 backdrop-blur-md border border-white/20 text-white rounded-[1.5rem] font-black text-[10px] uppercase tracking-widest hover:bg-white/10 transition">
                            Modo Piloto Automático
                        </button>
                    </div>
                </div>
                <div class="absolute top-[-20%] right-[-10%] w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
            </div>

            <!-- JOB SEARCH RESULTS (DYNAMIC) -->
            <div x-show="jobs.length > 0" x-transition class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="bg-white dark:bg-gray-800 rounded-[3rem] p-10 shadow-sm border border-gray-100 dark:border-gray-700 mb-8">
                    <div class="flex justify-between items-center mb-10">
                        <div>
                            <h3 class="font-black text-xl uppercase tracking-tighter">Oportunidades Encontradas</h3>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-1">Selecionadas via Google Jobs & LinkedIn.</p>
                        </div>
                        <button @click="jobs = []" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition">Limpar Busca</button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="job in jobs" :key="job.job_id">
                            <div class="group p-8 bg-gray-50 dark:bg-gray-900/50 rounded-[2rem] border border-gray-100 dark:border-gray-700 hover:border-blue-500 transition-all duration-300">
                                <div class="flex items-center gap-4 mb-6">
                                    <img :src="job.thumbnail" class="w-12 h-12 rounded-xl" alt="Logo">
                                    <div>
                                        <h4 class="font-black text-sm text-gray-900 dark:text-white uppercase leading-tight tracking-tight" x-text="job.title"></h4>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1" x-text="job.company_name"></p>
                                    </div>
                                </div>
                                <div class="space-y-4 mb-8">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 font-medium" x-text="job.description"></p>
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 bg-white dark:bg-gray-800 rounded-lg text-[9px] font-black uppercase tracking-widest text-gray-400 border border-gray-100 dark:border-gray-700" x-text="job.location"></span>
                                        <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-[9px] font-black uppercase tracking-widest text-blue-600" x-text="'via ' + job.via"></span>
                                    </div>
                                </div>
                                <button @click="startAnalysis(job)" class="w-full py-4 bg-gray-900 dark:bg-white dark:text-gray-900 text-white font-black rounded-xl text-[10px] uppercase tracking-widest group-hover:bg-blue-600 group-hover:text-white transition-all">
                                    Analisar & Disparar
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Stats & Tracking (Existing) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-500">
                    <div class="flex justify-between items-start mb-6"><span class="text-blue-500 text-[10px] font-black uppercase tracking-widest">Ativo</span></div>
                    <p class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-1">Total de Envios</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-4 tracking-tighter">{{ $stats['total'] }}</h3>
                    <div class="h-10 w-full"><canvas id="sparklineSent"></canvas></div>
                </div>
                <!-- ... other stats ... -->
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-500">
                    <div class="flex justify-between items-start mb-6"><span class="text-green-500 text-[10px] font-black uppercase tracking-widest">Sucesso</span></div>
                    <p class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-1">Entregues</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-4 tracking-tighter">{{ $stats['sent'] }}</h3>
                    <div class="h-10 w-full"><canvas id="sparklineDelivered"></canvas></div>
                </div>
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-500">
                    <div class="flex justify-between items-start mb-6"><span class="text-yellow-500 text-[10px] font-black uppercase tracking-widest">Interação</span></div>
                    <p class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-1">Lidos (RH)</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-4 tracking-tighter">0</h3>
                    <div class="h-10 w-full"><canvas id="sparklineRead"></canvas></div>
                </div>
                <div class="group bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl transition-all duration-500">
                    <div class="flex justify-between items-start mb-6"><span class="text-purple-500 text-[10px] font-black uppercase tracking-widest">Resultado</span></div>
                    <p class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-1">Respostas</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-4 tracking-tighter">0</h3>
                    <div class="h-10 w-full"><canvas id="sparklineResponses"></canvas></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-[3rem] shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-10 border-b border-gray-50 dark:border-gray-700"><h3 class="font-black text-xl tracking-tight uppercase">Rastreamento Ativo</h3></div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 dark:bg-gray-900/50 text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                <tr><th class="px-10 py-6">Empresa / Canal</th><th class="px-10 py-6">Data</th><th class="px-10 py-6 text-right">Status</th></tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                @forelse($applications as $app)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition duration-300">
                                        <td class="px-10 py-8">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-900 flex items-center justify-center text-gray-900 dark:text-white font-black text-xs">{{ substr($app->company_name, 0, 1) }}</div>
                                                <div>
                                                    <p class="font-black text-gray-900 dark:text-white uppercase text-xs tracking-widest">{{ $app->company_name }}</p>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <span class="text-[9px] font-black text-blue-500 uppercase tracking-widest">WhatsApp AI</span>
                                                        <span class="text-[9px] font-black px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 rounded-md">{{ $app->match_score ?? 0 }}% Match</span>
                                                    </div>
                                                    <p class="text-[10px] text-gray-400 font-medium italic mt-2 max-w-md">"{{ $app->strategy_note ?? 'Análise automática realizada.' }}"</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-10 py-8">
                                            <p class="text-xs font-black text-gray-700 dark:text-gray-300">{{ $app->created_at->format('d M, Y') }}</p>
                                            <p class="text-[9px] text-gray-400 font-black uppercase mt-0.5">{{ $app->created_at->format('H:i') }}</p>
                                        </td>
                                        <td class="px-10 py-8 text-right">
                                            @php
                                                $statusConfig = [
                                                    'sent' => ['class' => 'bg-gray-100 text-gray-500', 'label' => 'Enviado'],
                                                    'delivered' => ['class' => 'bg-blue-100 text-blue-600', 'label' => 'Entregue'],
                                                    'read' => ['class' => 'bg-green-100 text-green-600', 'label' => 'Lido pelo RH'],
                                                ][$app->delivery_status] ?? ['class' => 'bg-gray-100 text-gray-500', 'label' => 'Enviado'];
                                            @endphp
                                            <span class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-[0.1em] {{ $statusConfig['class'] }}">{{ $statusConfig['label'] }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-10 py-24 text-center"><p class="text-gray-400 font-black text-[10px] uppercase tracking-[0.2em]">Sem candidaturas ativas</p></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="space-y-8">
                    <div class="bg-white dark:bg-gray-800 p-10 rounded-[3rem] shadow-sm border border-gray-100 dark:border-gray-700">
                        <h3 class="font-black text-xl mb-8 uppercase tracking-tighter">Análise IA</h3>
                        <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-[2rem] border border-gray-100 dark:border-gray-700">
                            <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-300 font-medium italic">"Seu perfil está otimizado para cargos de tecnologia."</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- PREVIEW MODAL -->
        <template x-if="showPreview">
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-950/60 backdrop-blur-sm animate-in fade-in duration-300">
                <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-[3rem] shadow-2xl border border-white/10 overflow-hidden" @click.away="showPreview = false">
                    <div class="bg-gray-900 p-8 text-white flex justify-between items-center">
                        <h3 class="text-xl font-black uppercase tracking-tighter">Análise de Prospecção</h3>
                        <span class="px-4 py-2 bg-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest" x-text="previewData.match + '% Compatível'"></span>
                    </div>
                    <div class="p-10 space-y-8">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Empresa Alvo</p>
                            <h4 class="text-lg font-black uppercase text-gray-900 dark:text-white" x-text="previewData.company_name"></h4>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Estratégia da IA</p>
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-2xl">
                                <p class="text-sm font-bold text-blue-700 dark:text-blue-400 italic" x-text="'&quot;' + previewData.strategy + '&quot;'"></p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Pitch Gerado pela IA</p>
                            <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300 font-medium" x-text="previewData.pitch"></p>
                            </div>
                        </div>

                        <!-- Aviso do novo fluxo -->
                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-2xl">
                            <p class="text-[11px] font-bold text-blue-700 dark:text-blue-300 leading-relaxed">
                                📲 <strong>Como funciona:</strong> Ao confirmar, você receberá este pitch no seu <strong>WhatsApp</strong> e <strong>e-mail</strong> — pronto para copiar e aplicar direto na vaga.
                            </p>
                        </div>

                        <div class="flex gap-4 pt-2">
                            <button @click="showPreview = false" class="flex-1 py-5 rounded-2xl border border-gray-200 dark:border-gray-700 text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-900 transition">Cancelar</button>
                            <button @click="confirmSend()" class="flex-[2] py-5 bg-blue-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 transition shadow-xl shadow-blue-500/20" :disabled="sending">
                                <span x-show="!sending">Receber Pitch no WhatsApp & Email</span>
                                <span x-show="sending">Enviando...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- LOADING OVERLAY -->
        <template x-if="loading">
            <div class="fixed inset-0 z-[110] flex flex-col items-center justify-center bg-white/80 dark:bg-slate-950/80 backdrop-blur-md">
                <div class="w-16 h-16 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mb-6"></div>
                <p class="text-xs font-black uppercase tracking-[0.3em] text-gray-500 animate-pulse" x-text="loadingMessage"></p>
            </div>
        </template>
    </div>

    <script>
        function prospectingFlow() {
            return {
                loading: false,
                loadingMessage: 'IA Analisando...',
                showPreview: false,
                sending: false,
                jobs: [],
                previewData: { pitch: '', strategy: '', match: 0, company_name: '' },

                async fetchJobs() {
                    this.loading = true;
                    this.loadingMessage = 'Buscando Vagas Reais no Google Jobs...';
                    try {
                        const response = await fetch("{{ route('cv.search') }}");
                        this.jobs = await response.json();
                    } catch (e) {
                        alert('Erro ao buscar vagas.');
                    } finally {
                        this.loading = false;
                    }
                },

                async startAutopilot() {
                    if(!confirm('O Modo Piloto Automático irá buscar vagas e disparar candidaturas automaticamente em segundo plano. Deseja continuar?')) return;
                    
                    this.loading = true;
                    this.loadingMessage = 'Ativando Piloto Automático...';
                    try {
                        const response = await fetch("{{ route('cv.autopilot') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        const data = await response.json();
                        if (data.error) throw new Error(data.error);

                        alert('Piloto Automático Ativado! Você pode fechar esta página, a IA continuará trabalhando por você.');
                    } catch (e) {
                        alert(e.message || 'Erro ao ativar piloto automático.');
                    } finally {
                        this.loading = false;
                    }
                },

                async startAnalysis(job) {
                    this.loading = true;
                    this.loadingMessage = 'IA Analisando Vaga da ' + job.company_name + '...';
                    try {
                        const response = await fetch("{{ route('cv.analyze') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                company_name: job.company_name,
                                title: job.title,
                                description: job.description,
                                location: job.location,
                                via: job.via,
                                job_url: job.job_url ?? null,
                            })
                        });

                        const data = await response.json();
                        if (data.error) throw new Error(data.error);

                        this.previewData = {
                            ...data,
                            company_name: job.company_name,
                            title: job.title,
                            location: job.location,
                            via: job.via,
                            job_url: job.job_url ?? null,
                        };
                        this.showPreview = true;
                    } catch (e) {
                        alert(e.message || 'Erro ao processar análise.');
                    } finally {
                        this.loading = false;
                    }
                },

                async confirmSend() {
                    this.sending = true;
                    try {
                        const response = await fetch("{{ route('cv.send') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(this.previewData)
                        });

                        const data = await response.json();
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
    </script>

    <!-- Scripts for Sparklines -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const sparkOptions = (color) => ({
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { display: false }, y: { display: false } },
            elements: { point: { radius: 0 }, line: { tension: 0.4, borderWidth: 3, borderColor: color } }
        });
        new Chart(document.getElementById('sparklineSent'), { type: 'line', data: { labels: [1,2,3,4,5,6], datasets: [{ data: [10, 15, 8, 22, 18, 20] }] }, options: sparkOptions('#3b82f6') });
        new Chart(document.getElementById('sparklineDelivered'), { type: 'line', data: { labels: [1,2,3,4,5,6], datasets: [{ data: [5, 8, 12, 10, 15, 18] }] }, options: sparkOptions('#10b981') });
        new Chart(document.getElementById('sparklineRead'), { type: 'line', data: { labels: [1,2,3,4,5,6], datasets: [{ data: [0, 2, 4, 3, 5, 4] }] }, options: sparkOptions('#f59e0b') });
        new Chart(document.getElementById('sparklineResponses'), { type: 'line', data: { labels: [1,2,3,4,5,6], datasets: [{ data: [0, 1, 3, 2, 4, 3] }] }, options: sparkOptions('#a855f7') });
    </script>

    <style>
        .animate-in { animation: fadeIn 0.8s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-app-layout>
