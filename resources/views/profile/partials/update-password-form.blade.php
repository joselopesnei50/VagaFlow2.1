<section class="space-y-6">
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-6">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="current_password">Senha Atual</label>
                <input id="current_password" name="current_password" type="password" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition shadow-sm dark:text-white" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="password">Nova Senha</label>
                    <input id="password" name="password" type="password" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition shadow-sm dark:text-white" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1" for="password_confirmation">Confirmar Nova Senha</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition shadow-sm dark:text-white" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-gray-900 dark:bg-white dark:text-gray-900 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-600 dark:hover:bg-blue-500 dark:hover:text-white transition shadow-lg">
                Atualizar Senha
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-500 font-bold"
                >Senha atualizada!</p>
            @endif
        </div>
    </form>
</section>
