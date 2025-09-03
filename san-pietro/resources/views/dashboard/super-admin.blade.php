<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Metriche Globali -->
            <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Companies Totali</h3>
                <p class="text-3xl font-bold">{{ $totalCompanies }}</p>
                <p class="text-sm mt-2">Attive: {{ $activeCompanies }}</p>
            </div>
            
            <div class="bg-green-100 dark:bg-green-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Utenti Totali</h3>
                <p class="text-3xl font-bold">{{ $totalUsers }}</p>
            </div>
            
            <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Companies Recenti</h3>
                <p class="text-3xl font-bold">{{ $latestCompanies->count() }}</p>
            </div>
            
            <div class="bg-purple-100 dark:bg-purple-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Inviti Pendenti</h3>
                <p class="text-3xl font-bold">{{ $pendingInvites }}</p>
            </div>
        </div>

        <!-- Gestione Companies -->
        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 mb-8">
            <h3 class="text-xl font-semibold mb-4">Gestione Companies</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($latestCompanies as $company)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.companies.show', $company) }}" class="hover:text-blue-600">
                                    {{ $company->name }}
                                    @if($company->parent_id)
                                        <span class="text-sm text-gray-500">(Child di {{ $company->parentCompany->name }})</span>
                                    @endif
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($company->type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($company->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Attiva
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inattiva
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.companies.edit', $company) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifica</a>
                                <form action="{{ route('admin.companies.toggle-status', $company) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        {{ $company->is_active ? 'Disattiva' : 'Attiva' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- System Tools -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Manutenzione Sistema</h3>
                <div class="space-y-2">
                    <button class="w-full px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                        Cache Clear
                    </button>
                    <button class="w-full px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                        Ottimizza Database
                    </button>
                    <button class="w-full px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">
                        Backup System
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Log Sistema</h3>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    @foreach(range(1, 5) as $i)
                    <div class="p-2 bg-gray-50 dark:bg-gray-800 rounded">
                        <p class="text-sm">Activity {{ $i }}</p>
                        <span class="text-xs text-gray-500">2 minuti fa</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="#" class="block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500">
                        Nuova Company
                    </a>
                    <a href="#" class="block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">
                        Nuovo Utente
                    </a>
                    <a href="#" class="block px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-500">
                        Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
