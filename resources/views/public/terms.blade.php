<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos de Uso - JobBot AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased">
    <nav class="bg-white border-b border-slate-200 py-6">
        <div class="max-w-4xl mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-black">J</div>
                <span class="text-xl font-black tracking-tighter">JobBot <span class="text-blue-600">AI</span></span>
            </a>
            <a href="/" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition">Voltar para Home</a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-20">
        <div class="bg-white p-12 md:p-20 rounded-[3rem] shadow-sm border border-slate-200">
            <h1 class="text-4xl md:text-5xl font-black tracking-tighter mb-10">Termos de <span class="text-blue-600">Uso</span></h1>
            
            <div class="prose prose-slate max-w-none prose-headings:font-black prose-headings:tracking-tight prose-p:leading-relaxed text-slate-600">
                {!! nl2br(e($content)) !!}
            </div>
        </div>
    </main>

    <footer class="py-10 text-center text-xs font-bold text-slate-400">
        &copy; 2026 JobBot AI. Todos os direitos reservados.
    </footer>
</body>
</html>
