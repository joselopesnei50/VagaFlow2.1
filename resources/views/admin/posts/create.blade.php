<x-app-layout>
    @section('title', 'Criar Novo Post')

    <div class="max-w-4xl mx-auto space-y-10 animate-in fade-in duration-700">
        <div class="bg-white dark:bg-slate-800 p-12 rounded-[3rem] shadow-sm border border-slate-100 dark:border-slate-700">
            <h2 class="text-3xl font-black tracking-tighter uppercase mb-12">Novo Conteúdo</h2>
            
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Título do Post</label>
                        <input type="text" name="title" required class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl p-4 focus:ring-2 focus:ring-primary transition shadow-inner dark:text-white">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Imagem de Capa</label>
                        <input type="file" name="image" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl p-4 focus:ring-2 focus:ring-primary transition shadow-inner dark:text-white">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Resumo (Excerpt)</label>
                    <textarea name="excerpt" rows="3" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl p-4 focus:ring-2 focus:ring-primary transition shadow-inner dark:text-white"></textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 px-1">Conteúdo Completo</label>
                    <textarea name="content" rows="12" required class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-2xl p-4 focus:ring-2 focus:ring-primary transition shadow-inner dark:text-white"></textarea>
                </div>

                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" checked class="w-6 h-6 rounded-lg border-slate-200 text-primary focus:ring-primary">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Publicar Imediatamente</span>
                    </label>
                </div>

                <div class="pt-8 border-t border-slate-50 dark:border-slate-700 flex justify-end gap-4">
                    <a href="{{ route('admin.posts.index') }}" class="px-10 py-5 bg-slate-100 dark:bg-slate-900 text-slate-400 font-black rounded-2xl hover:text-slate-900 dark:hover:text-white transition uppercase text-[10px] tracking-widest">Cancelar</a>
                    <button type="submit" class="px-12 py-5 bg-primary text-white font-black rounded-2xl shadow-xl hover:bg-blue-700 transition uppercase text-[10px] tracking-widest">Salvar Post</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
