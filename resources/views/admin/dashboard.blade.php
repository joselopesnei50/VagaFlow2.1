<x-app-layout>
    @section('title', 'Console de Administração')

    <div class="space-y-8 animate-in fade-in duration-700">
        
        <!-- Welcome Section with Glassmorphism -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-blue-700 to-purple-800 rounded-[2.5rem] p-10 text-white shadow-2xl shadow-indigo-500/20">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h3 class="text-3xl font-black mb-2 tracking-tight uppercase leading-none">Bem-vindo, Comandante.</h3>
                    <p class="text-indigo-100 font-medium opacity-90 max-w-md">O JobBot AI está operando em alta performance. Confira os números globais da sua plataforma hoje.</p>
                </div>
                <div class="flex gap-4">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-[2rem] text-center min-w-[140px]">
                        <p class="text-[9px] font-black uppercase tracking-widest text-indigo-200 mb-1">Status API</p>
                        <div class="flex items-center justify-center gap-2">
                            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                            <span class="font-black text-xs uppercase tracking-widest">Online</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Abstract background elements -->
            <div class="absolute top-[-20%] right-[-10%] w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Metric Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Revenue Card -->
            <div class="group bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-500">
                <div class="flex justify-between items-start mb-6">
                    <span class="text-green-500 text-[10px] font-black uppercase tracking-widest">+12.5% Crescimento</span>
                </div>
                <p class="text-[10px] font-black text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-1">Receita Global</p>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-4 tracking-tighter">R$ {{ number_format($stats['total_revenue'], 2, ',', '.') }}</h3>
                <div class="h-10 w-full"><canvas id="miniChartRevenue"></canvas></div>
            </div>

            <!-- Users Card -->
            <div class="group bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-500">
                <div class="flex justify-between items-start mb-6">
                    <span class="text-blue-500 text-[10px] font-black uppercase tracking-widest">Base de Dados</span>
                </div>
                <p class="text-[10px] font-black text-gray-600 dark:text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-1">Candidatos Ativos</p>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-4 tracking-tighter">{{ $stats['total_users'] }}</h3>
                <div class="h-10 w-full"><canvas id="miniChartUsers"></canvas></div>
            </div>

            <!-- Conversion Card -->
            <div class="group bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-500">
                <div class="flex justify-between items-start mb-6">
                    <span class="text-amber-500 text-[10px] font-black uppercase tracking-widest">Conversão</span>
                </div>
                <p class="text-[10px] font-black text-gray-600 dark:text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-1">Taxa Premium</p>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-4 tracking-tighter">{{ $stats['total_users'] > 0 ? round(($stats['premium_users'] / $stats['total_users']) * 100, 1) : 0 }}%</h3>
                <div class="h-10 w-full"><canvas id="miniChartConv"></canvas></div>
            </div>

            <!-- AI Dispatches Today -->
            <div class="group bg-white dark:bg-gray-800 p-8 rounded-[2.5rem] shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-500">
                <div class="flex justify-between items-start mb-6">
                    <span class="text-indigo-500 text-[10px] font-black uppercase tracking-widest">Tempo Real</span>
                </div>
                <p class="text-[10px] font-black text-gray-600 dark:text-gray-600 dark:text-gray-400 uppercase tracking-widest mb-1">Envios Hoje</p>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-4 tracking-tighter">{{ $stats['total_today'] }}</h3>
                <div class="h-10 w-full"><canvas id="miniChartToday"></canvas></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Analysis Chart -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-10 rounded-[3rem] shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-10">
                    <div>
                        <h3 class="text-xl font-black tracking-tight uppercase leading-none">Análise de Crescimento</h3>
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-1">Performance Global Mensal.</p>
                    </div>
                </div>
                <div class="h-[350px] w-full">
                    <canvas id="mainDashboardChart"></canvas>
                </div>
            </div>

            <!-- Side Panels -->
            <div class="space-y-8">
                <!-- Recent Activity -->
                <div class="bg-white dark:bg-gray-800 p-10 rounded-[3rem] shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-black mb-8 uppercase tracking-tighter">Últimos Pagamentos</h3>
                    <div class="space-y-8">
                        @foreach($stats['recent_payments'] as $payment)
                            <div class="flex items-center justify-between group cursor-pointer border-b border-gray-200 dark:border-gray-700 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-900 flex items-center justify-center text-gray-900 dark:text-white font-black text-xs">
                                        {{ substr($payment->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-widest transition group-hover:text-indigo-600">{{ $payment->user->name }}</p>
                                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">{{ $payment->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-black text-gray-900 dark:text-white tracking-tighter">R$ {{ number_format($payment->amount, 2, ',', '.') }}</p>
                                    <span class="text-[9px] font-black text-green-500 uppercase">Sucesso</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="w-full mt-10 py-5 bg-gray-50 dark:bg-gray-900 rounded-[1.5rem] text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                        Relatório Completo
                    </button>
                </div>

                <!-- Recent Candidates -->
                <div class="bg-white dark:bg-gray-800 p-10 rounded-[3rem] shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-black mb-8 uppercase tracking-tighter">Últimos Candidatos</h3>
                    <div class="space-y-8">
                        @foreach($stats['recent_users'] as $u)
                            <div class="flex items-center justify-between group cursor-pointer border-b border-gray-200 dark:border-gray-700 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-900 flex items-center justify-center text-gray-900 dark:text-white font-black text-xs">
                                        {{ substr($u->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-widest transition group-hover:text-indigo-600">{{ $u->name }}</p>
                                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">{{ $u->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 rounded-lg text-[8px] font-black uppercase tracking-widest">{{ $u->is_premium ? 'Premium' : 'Free' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Fast Actions -->
                <div class="bg-indigo-600 rounded-[3rem] p-10 text-white shadow-xl shadow-indigo-500/20 relative overflow-hidden">
                    <h3 class="text-xl font-black mb-2 relative z-10 uppercase tracking-tighter leading-none">Central de Comando</h3>
                    <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest mb-8 opacity-80 relative z-10">Módulos Críticos.</p>
                    
                    <div class="grid grid-cols-2 gap-3 relative z-10">
                        <a href="{{ route('admin.plans.index') }}" class="p-5 bg-white/10 hover:bg-white/20 rounded-2xl border border-white/10 transition-all text-center">
                            <span class="text-[10px] font-black uppercase tracking-widest">Planos</span>
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="p-5 bg-white/10 hover:bg-white/20 rounded-2xl border border-white/10 transition-all text-center">
                            <span class="text-[10px] font-black uppercase tracking-widest">Settings</span>
                        </a>
                        <a href="{{ route('admin.posts.index') }}" class="p-5 bg-white/10 hover:bg-white/20 rounded-2xl border border-white/10 transition-all text-center">
                            <span class="text-[10px] font-black uppercase tracking-widest">Blog CMS</span>
                        </a>
                        <a href="#" class="p-5 bg-white/10 hover:bg-white/20 rounded-2xl border border-white/10 transition-all text-center">
                            <span class="text-[10px] font-black uppercase tracking-widest">Logs</span>
                        </a>
                    </div>
                </div>

                <!-- Search Logs (Monitoring) -->
                <div class="bg-white dark:bg-gray-800 p-10 rounded-[3rem] shadow-sm border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-black mb-8 uppercase tracking-tighter">Monitoramento de APIs</h3>
                    <div class="space-y-6">
                        @foreach($stats['recent_logs'] as $log)
                            <div class="p-4 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-[8px] font-black uppercase tracking-widest {{ $log->status === 'success' ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $log->service }} - {{ $log->status }}
                                    </span>
                                    <span class="text-[8px] text-gray-400 font-black uppercase">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-tight truncate">{{ $log->query }}</p>
                                @if($log->error_message)
                                    <p class="text-[8px] text-red-400 mt-1 font-medium italic truncate">{{ $log->error_message }}</p>
                                @endif
                                <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                    <span class="text-[8px] text-gray-400 uppercase font-black">Por: {{ $log->user->name ?? 'Sistema' }}</span>
                                    <span class="text-[8px] text-indigo-500 uppercase font-black">{{ $log->results_count }} resultados</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartOptions = (color) => ({
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { x: { display: false }, y: { display: false } },
            elements: { point: { radius: 0 }, line: { tension: 0.4, borderWidth: 3, borderColor: color } }
        });

        // Mini Charts
        new Chart(document.getElementById('miniChartRevenue'), { type: 'line', data: { labels: [1,2,3,4,5,6], datasets: [{ data: [10, 15, 8, 12, 18, 20] }] }, options: chartOptions('#6366f1') });
        new Chart(document.getElementById('miniChartUsers'), { type: 'line', data: { labels: [1,2,3,4,5,6], datasets: [{ data: [5, 10, 12, 8, 15, 18] }] }, options: chartOptions('#3b82f6') });
        new Chart(document.getElementById('miniChartConv'), { type: 'line', data: { labels: [1,2,3,4,5,6], datasets: [{ data: [2, 5, 4, 6, 8, 7] }] }, options: chartOptions('#f59e0b') });
        new Chart(document.getElementById('miniChartApps'), { type: 'line', data: { labels: [1,2,3,4,5,6], datasets: [{ data: [20, 40, 35, 50, 45, 60] }] }, options: chartOptions('#10b981') });

        // Main Chart
        const ctxMain = document.getElementById('mainDashboardChart').getContext('2d');
        const gradient = ctxMain.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

        new Chart(ctxMain, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago'],
                datasets: [{
                    label: 'Receita Global',
                    data: [1200, 1900, 1500, 2500, 2200, 3100, 3800, {{ $stats['total_revenue'] > 0 ? $stats['total_revenue'] : 4500 }}],
                    borderColor: '#6366f1',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                        ticks: { font: { weight: 'bold', size: 10 }, color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: 'bold', size: 10 }, color: '#94a3b8' }
                    }
                }
            }
        });
    </script>

    <style>
        .animate-in {
            animation: fadeIn 0.8s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>
