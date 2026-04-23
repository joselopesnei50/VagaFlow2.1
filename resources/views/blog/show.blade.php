<!DOCTYPE html>
<html lang="pt" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - JobBot AI Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .blog-content p { margin-bottom: 1.5rem; line-height: 1.8; color: #4b5563; }
        .blog-content h2 { font-weight: 900; font-size: 1.5rem; text-transform: uppercase; margin-top: 2rem; margin-bottom: 1rem; }
    </style>
</head>
<body class="bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100">
    <nav class="fixed w-full z-50 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-800">
        <div class="max-w-4xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-black text-sm">J</div>
                <span class="text-xl font-black tracking-tighter uppercase">JobBot <span class="text-blue-600">AI</span></span>
            </a>
            <a href="/" class="text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-blue-600 transition">Voltar para Home</a>
        </div>
    </nav>

    <article class="pt-40 pb-32">
        <div class="max-w-4xl mx-auto px-6">
            <div class="mb-12 space-y-6 text-center">
                <div class="flex gap-4 items-center justify-center">
                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-600">{{ $post->created_at->format('d M, Y') }}</span>
                    <span class="w-1.5 h-1.5 bg-slate-200 rounded-full"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Escrito por IA JobBot</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-black uppercase tracking-tighter leading-[0.9]">{{ $post->title }}</h1>
                <p class="text-xl text-slate-500 font-medium leading-relaxed max-w-2xl mx-auto italic">"{{ $post->excerpt }}"</p>
            </div>

            @if($post->image)
                <div class="aspect-video w-full rounded-[3rem] overflow-hidden mb-16 shadow-2xl">
                    <img src="{{ asset('storage/'.$post->image) }}" class="w-full h-full object-cover">
                </div>
            @endif

            <div class="blog-content prose prose-xl dark:prose-invert max-w-none">
                {!! nl2br(e($post->content)) !!}
            </div>

            <div class="mt-24 pt-12 border-t border-slate-100 dark:border-slate-800 flex flex-col items-center gap-8">
                <h3 class="text-2xl font-black uppercase tracking-tighter">Gostou deste insight?</h3>
                <p class="text-slate-500 text-center font-medium max-w-sm">Deixe nossa IA encontrar a vaga perfeita para você enquanto você foca na sua carreira.</p>
                <a href="{{ route('register') }}" class="px-12 py-5 bg-blue-600 text-white font-black rounded-2xl shadow-xl hover:bg-blue-700 transition uppercase text-[10px] tracking-widest">Ativar JobBot Agora</a>
            </div>
        </div>
    </article>
</body>
</html>
