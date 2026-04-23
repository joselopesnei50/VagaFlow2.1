<section class="space-y-6">
    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="name">Nome Completo</label>
                <input id="name" name="name" type="text" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition shadow-sm dark:text-white" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <!-- Email -->
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="email">E-mail Profissional</label>
                <input id="email" name="email" type="email" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition shadow-sm dark:text-white" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <!-- Target Role (Custom) -->
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="target_role">Cargo Desejado</label>
                <input id="target_role" name="target_role" type="text" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition shadow-sm dark:text-white" value="{{ old('target_role', $user->profile->target_role ?? '') }}" placeholder="Ex: Desenvolvedor Senior">
            </div>

            <!-- WhatsApp (Custom) -->
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="whatsapp_number">WhatsApp para Contato</label>
                <input id="whatsapp_number" name="whatsapp_number" type="text" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition shadow-sm dark:text-white" value="{{ old('whatsapp_number', $user->profile->whatsapp_number ?? '') }}" placeholder="5511999999999">
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                Salvar Alterações
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-500 font-bold"
                >Perfil atualizado com sucesso!</p>
            @endif
        </div>
    </form>
</section>
