<section class="space-y-6">
    <div class="flex items-center justify-between bg-red-50 dark:bg-red-900/10 p-6 rounded-2xl border border-red-100 dark:border-red-900/20">
        <div class="max-w-xl">
            <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-1">Ação Irreversível</p>
            <p class="text-sm text-red-500 font-medium">Ao excluir sua conta, todos os seus créditos, currículos e histórico de disparos serão apagados para sempre.</p>
        </div>
        
        <button 
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="bg-red-600 text-white px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-red-700 transition shadow-lg shadow-red-500/20 whitespace-nowrap"
        >
            Excluir Agora
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-10 bg-white dark:bg-gray-800 rounded-[2.5rem]">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                Você tem certeza? ⚠️
            </h2>

            <p class="mt-4 text-sm text-gray-500 font-medium leading-relaxed">
                Esta ação não pode ser desfeita. Por favor, digite sua senha para confirmar que deseja excluir permanentemente sua conta e todos os dados associados.
            </p>

            <div class="mt-8">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="password">Sua Senha de Confirmação</label>
                <input 
                    id="password"
                    name="password"
                    type="password"
                    class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-red-500 transition shadow-sm dark:text-white"
                    placeholder="Digite sua senha..."
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-10 flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-900 dark:hover:text-white transition">
                    Cancelar
                </button>

                <button type="submit" class="bg-red-600 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-700 transition shadow-lg shadow-red-500/20">
                    Confirmar Exclusão
                </button>
            </div>
        </form>
    </x-modal>
</section>
