<x-app-layout>
    @section('title', 'Gerenciar Blog')

    <div class="max-w-7xl mx-auto space-y-10 animate-in fade-in duration-700">
        <!-- Header -->
        <div class="bg-slate-900 rounded-[3rem] p-12 text-white shadow-2xl relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                <div>
                    <h2 class="text-4xl font-black tracking-tighter uppercase leading-none">Blog Engine</h2>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-4">Gestão de conteúdo e autoridade.</p>
                </div>
                <a href="{{ route('admin.posts.create') }}" class="px-10 py-5 bg-primary text-white font-black rounded-2xl shadow-xl hover:bg-blue-700 transition uppercase text-xs tracking-widest">Novo Post</a>
            </div>
            <div class="absolute top-[-20%] right-[-10%] w-64 h-64 bg-primary/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Posts List -->
        <div class="bg-white dark:bg-slate-800 rounded-[3rem] shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                            <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Título</th>
                            <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                            <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Data</th>
                            <th class="px-10 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                        @foreach($posts as $post)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50 transition">
                            <td class="px-10 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-900 rounded-xl overflow-hidden flex-shrink-0">
                                        @if($post->image)
                                            <img src="{{ asset('storage/'.$post->image) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <span class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $post->title }}</span>
                                </div>
                            </td>
                            <td class="px-10 py-6">
                                <span class="px-4 py-1.5 {{ $post->is_published ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} rounded-full text-[9px] font-black uppercase tracking-widest">
                                    {{ $post->is_published ? 'Publicado' : 'Rascunho' }}
                                </span>
                            </td>
                            <td class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase">{{ $post->created_at->format('d/m/Y') }}</td>
                            <td class="px-10 py-6 text-right space-x-4">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="text-[10px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-700">Editar</a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-[10px] font-black uppercase tracking-widest text-red-500 hover:text-red-700">Excluir</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-10 py-6 border-t border-slate-100 dark:border-slate-700">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
