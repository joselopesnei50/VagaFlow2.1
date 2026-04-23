<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestão de Assinaturas e Planos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Create Plan Form -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="font-black text-lg mb-4">Novo Plano</h3>
                    <form action="{{ route('admin.plans.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold mb-1">Nome do Plano</label>
                            <input type="text" name="name" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-xl" placeholder="Ex: Plano Gold">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1">Preço (R$)</label>
                            <input type="number" step="0.01" name="price" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-xl" placeholder="29.90">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1">Créditos</label>
                            <input type="number" name="credits" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-xl" placeholder="50">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1">ID Externo (AbacatePay)</label>
                            <input type="text" name="external_id" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-xl" placeholder="prod_abc123">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition shadow-lg">
                            Criar Plano
                        </button>
                    </form>
                </div>

                <!-- Plans List -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 dark:border-gray-700">
                        <h3 class="font-black text-lg">Planos Ativos</h3>
                    </div>
                    <div class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($plans as $plan)
                            <div class="p-6 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <div>
                                    <h4 class="font-black text-gray-900 dark:text-white">{{ $plan->name }}</h4>
                                    <p class="text-xs text-gray-500">ID: {{ $plan->external_id }} | {{ $plan->credits }} Créditos</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-black text-blue-600">R$ {{ number_format($plan->price / 100, 2, ',', '.') }}</div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-green-500">Ativo</span>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center text-gray-400">
                                Nenhum plano cadastrado.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
