<!DOCTYPE html>
<html lang="pt" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobBot AI - Automação Industrial de Candidaturas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: { primary: '#1e40af', secondary: '#10b981', navy: '#050505' },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .hero-text { letter-spacing: -0.05em; line-height: 0.9; }
    </style>
</head>
<body class="bg-white dark:bg-black text-black dark:text-white transition-colors duration-300 font-sans antialiased">

    <!-- Top Highlight Banner (Solid) -->
    <div class="bg-primary text-white py-4 text-center border-b-2 border-black dark:border-white">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-center gap-6">
            <span class="flex items-center gap-2 bg-black px-4 py-1.5 text-white">
                <span class="w-2 h-2 bg-green-500"></span>
                <span class="text-[9px] font-black uppercase tracking-widest">Tempo Real</span>
            </span>
            <p class="text-xs md:text-sm font-black uppercase tracking-[0.2em] flex items-center gap-2">
                <span class="text-green-400">{{ $totalToday }}</span> 
                Vagas Processadas e Enviadas Hoje
            </p>
        </div>
    </div>

    <!-- Navigation (Solid) -->
    <nav class="sticky top-0 z-50 bg-white dark:bg-black border-b-2 border-black dark:border-white">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-black dark:bg-white text-white dark:text-black flex items-center justify-center font-black text-xl">J</div>
                <span class="text-2xl font-black tracking-tighter">JobBot <span class="text-primary">AI</span></span>
            </div>
            
            <div class="hidden md:flex items-center gap-10 text-[10px] font-black uppercase tracking-widest">
                <!-- Bandeiras de Idioma -->
                <div class="flex items-center gap-2 text-base">
                    <span title="Português" class="cursor-pointer hover:scale-110 transition">🇧🇷</span>
                    <span title="Español" class="cursor-pointer hover:scale-110 transition">🇪🇸</span>
                    <span title="English" class="cursor-pointer hover:scale-110 transition">🇺🇸</span>
                </div>
                <div class="h-4 w-px bg-gray-300 dark:bg-gray-700 mx-2"></div>
                
                <a href="#funcionalidades" class="hover:text-primary transition">Funcionalidades</a>
                <a href="#precos" class="hover:text-primary transition">Preços</a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-black dark:bg-white text-white dark:text-black px-8 py-3 hover:bg-primary dark:hover:bg-primary dark:hover:text-white transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-primary transition">Entrar</a>
                    <a href="{{ route('register') }}" class="bg-black dark:bg-white text-white dark:text-black px-8 py-3 hover:bg-primary dark:hover:bg-primary dark:hover:text-white transition">Começar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Lifestyle Hero (Solid Layout) -->
    <section class="min-h-screen flex items-center bg-black text-white border-b-2 border-white relative overflow-hidden">
        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-luminosity" alt="Pessoas trabalhando juntas">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-20 items-center py-20 relative z-10">
            <div class="space-y-8">
                <div class="inline-block px-4 py-1.5 border-2 border-white text-[9px] font-black uppercase tracking-widest bg-black/50 backdrop-blur-md">
                    Sua Carreira, Nosso Foco.
                </div>
                <h2 class="text-7xl md:text-[7rem] font-black hero-text tracking-tighter uppercase leading-none drop-shadow-2xl">Humano. <br> Empatia. <br> Conexão.</h2>
                <p class="text-lg text-white font-bold leading-relaxed max-w-lg uppercase tracking-tight drop-shadow-md">
                    Construímos pontes entre você e seu próximo grande desafio. Nossa IA entende sua jornada e cria conexões reais.
                </p>
                <div class="pt-8 flex items-center gap-6">
                    <a href="{{ route('register') }}" class="px-12 py-6 bg-white text-black font-black hover:bg-primary hover:text-white transition uppercase text-xs tracking-widest border-2 border-white">Começar Minha Jornada</a>
                    <div class="hidden sm:block border-l-2 border-white/30 pl-6">
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest leading-tight">Match Score Médio<br><span class="text-white text-lg">87.5%</span></p>
                    </div>
                </div>
            </div>
            
            <div class="hidden md:block">
                <!-- Flat System Window instead of floating glass -->
                <div class="border-4 border-white bg-black p-8">
                    <div class="flex border-b-4 border-white pb-6 mb-6 justify-between items-center">
                        <div class="w-12 h-12 bg-primary"></div>
                        <div class="px-4 py-2 border-2 border-green-500 text-green-500 text-[8px] font-black uppercase tracking-widest">Match 92%</div>
                    </div>
                    <div class="space-y-4 mb-6">
                        <div class="h-4 w-full bg-gray-800"></div>
                        <div class="h-4 w-2/3 bg-gray-800"></div>
                    </div>
                    <div class="p-6 border-2 border-gray-800 bg-gray-900 mb-6">
                        <p class="text-[9px] text-gray-400 uppercase font-bold italic leading-relaxed">"Olá, notei que buscam alguém com experiência em escalabilidade..."</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="h-10 flex-1 bg-white"></div>
                        <div class="h-10 flex-1 border-2 border-white"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Engineering Flow (Solid/Grid) -->
    <section class="py-24 bg-white dark:bg-black border-b-2 border-black dark:border-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-20 space-y-4">
                <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tighter leading-none">Como criamos <span class="text-primary">Conexões.</span></h2>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-500">Um fluxo pensado para você</p>
            </div>

            <div class="relative">
                <div class="grid grid-cols-1 md:grid-cols-4 border-2 border-black dark:border-white divide-y-2 md:divide-y-0 md:divide-x-2 divide-black dark:divide-white relative z-10">
                    <!-- Step 1 -->
                    <div class="p-10 bg-gray-50 dark:bg-gray-900 hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-colors relative">
                        <span class="text-5xl font-black text-primary block mb-6">01</span>
                        <h4 class="text-sm font-black uppercase tracking-widest mb-4">Seu Perfil</h4>
                        <p class="text-[10px] font-bold uppercase tracking-tight leading-relaxed opacity-70">Nos conte sua história e anexe seu currículo.</p>
                        <!-- Arrow -->
                        <div class="hidden md:block absolute -right-6 top-1/2 -translate-y-1/2 z-20 text-black dark:text-white bg-white dark:bg-black rounded-full animate-pulse">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                    <!-- Step 2 -->
                    <div class="p-10 bg-gray-50 dark:bg-gray-900 hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-colors relative">
                        <span class="text-5xl font-black text-primary block mb-6">02</span>
                        <h4 class="text-sm font-black uppercase tracking-widest mb-4">Busca Ativa</h4>
                        <p class="text-[10px] font-bold uppercase tracking-tight leading-relaxed opacity-70">Encontramos as melhores empresas para o seu talento.</p>
                        <!-- Arrow -->
                        <div class="hidden md:block absolute -right-6 top-1/2 -translate-y-1/2 z-20 text-black dark:text-white bg-white dark:bg-black rounded-full animate-pulse" style="animation-delay: 200ms;">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                    <!-- Step 3 -->
                    <div class="p-10 bg-gray-50 dark:bg-gray-900 hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-colors relative">
                        <span class="text-5xl font-black text-primary block mb-6">03</span>
                        <h4 class="text-sm font-black uppercase tracking-widest mb-4">Empatia IA</h4>
                        <p class="text-[10px] font-bold uppercase tracking-tight leading-relaxed opacity-70">Adaptamos sua abordagem para encantar recrutadores.</p>
                        <!-- Arrow -->
                        <div class="hidden md:block absolute -right-6 top-1/2 -translate-y-1/2 z-20 text-black dark:text-white bg-white dark:bg-black rounded-full animate-pulse" style="animation-delay: 400ms;">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                    <!-- Step 4 -->
                    <div class="p-10 bg-primary text-white">
                        <span class="text-5xl font-black block mb-6 italic">WIN</span>
                        <h4 class="text-sm font-black uppercase tracking-widest mb-4">Conexão</h4>
                        <p class="text-[10px] font-bold uppercase tracking-tight leading-relaxed opacity-90">Sua mensagem entregue com sucesso e humanidade.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section (Super Hero Layout) -->
    <section id="funcionalidades" class="bg-gray-50 dark:bg-gray-900 border-b-2 border-black dark:border-white">
        <!-- Feature 01 -->
        <div class="border-b-2 border-black dark:border-white bg-white dark:bg-black relative overflow-hidden group">
            <div class="max-w-7xl mx-auto px-6 py-32 md:py-48 grid md:grid-cols-2 gap-20 items-center relative z-10">
                <div class="space-y-6">
                    <div class="text-[8rem] leading-none font-black text-gray-100 dark:text-gray-900 absolute -top-10 -left-10 select-none z-0 group-hover:text-primary/10 transition duration-700">01</div>
                    <div class="relative z-10">
                        <h3 class="text-6xl md:text-7xl font-black uppercase tracking-tighter mb-8 leading-none">Cards de <br><span class="text-primary">Oportunidade.</span></h3>
                        <p class="text-xl md:text-2xl font-bold uppercase tracking-tight text-gray-500 max-w-md">Visualize vagas reais extraídas diretamente do Google Jobs e LinkedIn com compatibilidade instantânea.</p>
                    </div>
                </div>
                <div class="hidden md:flex justify-end items-center relative z-10">
                    <div class="w-40 h-40 bg-black dark:bg-white text-white dark:text-black flex items-center justify-center rounded-full animate-bounce">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature 02 -->
        <div class="border-b-2 border-black dark:border-white bg-black text-white relative overflow-hidden group">
            <div class="max-w-7xl mx-auto px-6 py-32 md:py-48 grid md:grid-cols-2 gap-20 items-center relative z-10">
                <div class="hidden md:flex justify-start items-center relative z-10">
                    <div class="w-40 h-40 bg-primary text-white flex items-center justify-center rounded-full animate-bounce" style="animation-delay: 200ms;">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </div>
                </div>
                <div class="space-y-6 md:text-right">
                    <div class="text-[8rem] leading-none font-black text-gray-900 absolute -top-10 right-0 select-none z-0 group-hover:text-primary/20 transition duration-700">02</div>
                    <div class="relative z-10">
                        <h3 class="text-6xl md:text-7xl font-black uppercase tracking-tighter mb-8 leading-none">Piloto <br><span class="text-primary">Automático.</span></h3>
                        <p class="text-xl md:text-2xl font-bold uppercase tracking-tight text-gray-400 max-w-md md:ml-auto">Nossa IA trabalha 24/7 buscando, analisando e disparando candidaturas enquanto você foca no que importa.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature 03 -->
        <div class="bg-white dark:bg-black relative overflow-hidden group">
            <div class="max-w-7xl mx-auto px-6 py-32 md:py-48 grid md:grid-cols-2 gap-20 items-center relative z-10">
                <div class="space-y-6">
                    <div class="text-[8rem] leading-none font-black text-gray-100 dark:text-gray-900 absolute -top-10 -left-10 select-none z-0 group-hover:text-primary/10 transition duration-700">03</div>
                    <div class="relative z-10">
                        <h3 class="text-6xl md:text-7xl font-black uppercase tracking-tighter mb-8 leading-none">Alertas <br><span class="text-primary">Diretos.</span></h3>
                        <p class="text-xl md:text-2xl font-bold uppercase tracking-tight text-gray-500 max-w-md">Receba notificações instantâneas no WhatsApp sempre que uma candidatura for enviada estrategicamente.</p>
                    </div>
                </div>
                <div class="hidden md:flex justify-end items-center relative z-10">
                    <div class="w-40 h-40 bg-black dark:bg-white text-white dark:text-black flex items-center justify-center rounded-full animate-pulse">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section (Flat Brutalist) -->
    <section id="precos" class="py-32 bg-white dark:bg-black border-b-2 border-black dark:border-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20 space-y-4">
                <h2 class="text-5xl font-black tracking-tighter uppercase leading-none">Planos de Performance.</h2>
                <p class="text-xs font-black uppercase tracking-widest text-gray-500">Escalabilidade para sua carreira.</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-10 max-w-4xl mx-auto">
                <!-- Free Plan -->
                <div class="p-12 border-4 border-black dark:border-white bg-white dark:bg-black">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4">Acesso Limitado</p>
                    <h4 class="text-5xl font-black mb-8 italic tracking-tighter">R$ 0<span class="text-sm font-bold not-italic text-gray-500 uppercase"></span></h4>
                    <ul class="space-y-4 mb-12 text-[10px] font-black uppercase tracking-widest text-gray-600 dark:text-gray-400 border-t-2 border-gray-200 dark:border-gray-800 pt-8">
                        <li class="flex items-center gap-3"><span class="w-2 h-2 bg-black dark:bg-white"></span> 2 Créditos Iniciais</li>
                        <li class="flex items-center gap-3"><span class="w-2 h-2 bg-black dark:bg-white"></span> Análise Manual ou Piloto Limitado</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full py-5 text-center border-2 border-black dark:border-white text-[10px] font-black uppercase tracking-widest hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition">Criar Conta</a>
                </div>

                <!-- Premium Plan -->
                <div class="p-12 border-4 border-primary bg-primary text-white">
                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-200 mb-4">Plano Pro</p>
                    <h4 class="text-5xl font-black mb-8 italic tracking-tighter">R$ 49<span class="text-sm font-bold not-italic text-blue-200 uppercase"> /mês</span></h4>
                    <ul class="space-y-4 mb-12 text-[10px] font-black uppercase tracking-widest border-t-2 border-blue-600 pt-8">
                        <li class="flex items-center gap-3"><span class="w-2 h-2 bg-white"></span> 30 Envios Estratégicos</li>
                        <li class="flex items-center gap-3"><span class="w-2 h-2 bg-white"></span> Automação Total (Autopilot)</li>
                        <li class="flex items-center gap-3"><span class="w-2 h-2 bg-white"></span> Alertas WhatsApp em Tempo Real</li>
                    </ul>
                    <a href="{{ route('register') }}" class="block w-full py-5 text-center bg-white text-primary text-[10px] font-black uppercase tracking-widest hover:bg-gray-100 transition">Assinar Plano</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest from Blog (Solid) -->
    <section class="py-32 bg-gray-50 dark:bg-gray-900 border-b-2 border-black dark:border-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div class="space-y-4">
                    <h2 class="text-5xl font-black tracking-tighter uppercase leading-none">Inteligência Carreira.</h2>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-500">Insights exclusivos da nossa IA</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                @forelse($recentPosts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="group block border-2 border-black dark:border-white bg-white dark:bg-black">
                    <div class="aspect-video bg-gray-200 dark:bg-gray-800 border-b-2 border-black dark:border-white overflow-hidden">
                        @if($post->image)
                            <img src="{{ asset('storage/'.$post->image) }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 font-black text-4xl italic">JOBBOT</div>
                        @endif
                    </div>
                    <div class="p-8 space-y-4">
                        <div class="flex gap-4 items-center">
                            <span class="text-[9px] font-black uppercase tracking-widest text-primary">{{ $post->created_at->format('d M, Y') }}</span>
                        </div>
                        <h3 class="text-3xl font-black uppercase tracking-tighter leading-tight">{{ $post->title }}</h3>
                        <p class="text-gray-500 text-sm font-medium leading-relaxed line-clamp-2">{{ $post->excerpt }}</p>
                    </div>
                </a>
                @empty
                @for($i=0; $i<2; $i++)
                <div class="border-2 border-dashed border-gray-400 p-8 bg-transparent">
                    <div class="aspect-video bg-gray-200 dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 mb-8 flex items-center justify-center">
                        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Aguardando Insights...</span>
                    </div>
                </div>
                @endfor
                @endforelse
            </div>
        </div>
    </section>

    <!-- Mega Footer (Nixtio Reference - Solid Version) -->
    <footer class="bg-black text-white py-24">
        <!-- Top Nav -->
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-start md:items-center justify-between mb-20 gap-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white text-black font-black text-xl flex items-center justify-center">N</div>
            </div>
            <div class="flex flex-wrap gap-8 md:gap-16 text-[10px] font-black uppercase tracking-widest text-gray-400">
                <a href="https://wa.me/5511999999999" class="hover:text-white transition flex items-center gap-2">WhatsApp</a>
                <a href="#" class="hover:text-white transition flex items-center gap-2">Instagram</a>
                <a href="mailto:contato@jobbot.com" class="hover:text-white transition flex items-center gap-2">E-mail</a>
            </div>
            <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-black font-black rounded-full text-[10px] uppercase tracking-widest hover:bg-gray-200 transition">Iniciar Projeto</a>
        </div>

        <div class="max-w-7xl mx-auto px-6">
            <!-- Massive Brand Title matching Nixtio -->
            <h2 class="text-[15vw] font-black leading-[0.8] tracking-tighter mb-20 select-none">JobBot</h2>

            <div class="grid md:grid-cols-2 gap-20 items-end">
                <div class="space-y-12">
                    <!-- Stats matching Nixtio -->
                    <div class="flex flex-wrap gap-12 text-[10px] font-black text-white uppercase tracking-widest">
                        <div>
                            +50 Colaboradores
                        </div>
                        <div>
                            6 Países
                        </div>
                        <div>
                            Fundado em 2026
                        </div>
                    </div>

                    <!-- Description matching Nixtio -->
                    <p class="max-w-md text-xl font-medium text-gray-400 leading-relaxed">
                        <strong class="text-white">Criamos automações digitais que se destacam e escalam.</strong> Através de design de IA sob medida, desenvolvimento e engenharia que transformam candidaturas em entrevistas, contatos em clientes recorrentes e ideias em casos de sucesso.
                    </p>
                </div>

                <!-- Contact Card matching Nixtio -->
                <div class="flex justify-start md:justify-end">
                    <div class="bg-white text-black p-4 rounded-3xl flex flex-col gap-4 w-64">
                        <div class="w-full aspect-square rounded-2xl overflow-hidden bg-gray-200 relative">
                            <img src="https://ui-avatars.com/api/?name=Arsen&background=1e40af&color=fff&size=400" class="w-full h-full object-cover" alt="Support">
                            <div class="absolute bottom-2 left-2 bg-black/50 backdrop-blur-sm text-white text-[8px] font-black px-2 py-1 uppercase">Do processo de inovação</div>
                        </div>
                        <div>
                            <p class="text-[8px] font-black text-gray-500 leading-tight uppercase">Diretor de<br>Comunicações</p>
                            <h4 class="text-xl font-black mt-2">Arsen</h4>
                            <a href="#" class="mt-4 flex items-center justify-between w-full px-6 py-3 bg-black text-white rounded-full text-[10px] font-black uppercase">
                                Vamos Conversar
                                <span class="w-2 h-2 bg-white rounded-full"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
