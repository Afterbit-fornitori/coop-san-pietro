<x-app-layout>
    @php
    $action = $company->is_active ? 'disattivare' : 'attivare';
    @endphp
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dettagli Azienda') }}
            </h2>
            <div>
                <a href="{{ route('admin.companies.edit', $company) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Modifica') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Company Details -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Informazioni Azienda') }}</h3>

                    <div class="mt-6 space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Nome')" />
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $company->name }}</p>
                        </div>

                        <div>
                            <x-input-label for="domain" :value="__('Dominio')" />
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $company->domain }}</p>
                        </div>

                        <div>
                            <x-input-label for="type" :value="__('Tipo')" />
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($company->type) }}</p>
                        </div>

                        <div>
                            <x-input-label for="status" :value="__('Stato')" />
                            <p class="mt-1">
                                @if($company->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Attiva</span>
                                @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Inattiva</span>
                                @endif
                            </p>
                        </div>

                        @if($company->parent_id)
                        <div>
                            <x-input-label for="parent" :value="__('Azienda Madre')" />
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ $company->parentCompany->name }}
                            </p>
                        </div>
                        @endif

                        <div>
                            <x-input-label for="settings" :value="__('Impostazioni')" />
                            <pre class="mt-1 text-sm text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 p-4 rounded">{{ json_encode($company->settings, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Users -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Utenti') }}</h3>
                        <a href="{{ route('admin.users.create') }}" class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded">
                            {{ __('Aggiungi Utente') }}
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruolo</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($company->users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @foreach($user->getRoleNames() as $role)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $role }}
                                        </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifica</a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Sei sicuro di voler eliminare questo utente?')">
                                                Elimina
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-red-600 dark:text-red-400">{{ __('Zona Pericolosa') }}</h3>

                    <div class="mt-6 flex space-x-4">
                        <form action="{{ route('admin.companies.toggle-status', $company->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-700"
                                onclick="return confirm('Sei sicuro di voler {{ $action }} questa azienda?')">>
                                {{ $company->is_active ? 'Disattiva Azienda' : 'Attiva Azienda' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700" onclick="return confirm('Sei sicuro di voler eliminare questa azienda? Questa azione non può essere annullata.')">
                                Elimina Azienda
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>