<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Company Metrics -->
            <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Utenti</h3>
                <p class="text-3xl font-bold">{{ $usersCount }}</p>
                <p class="text-sm mt-2">Attivi: {{ $activeUsersCount }}</p>
            </div>
            
            <div class="bg-green-100 dark:bg-green-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">DDT Totali</h3>
                <p class="text-3xl font-bold">0</p>
                <p class="text-sm mt-2">In attesa: 0</p>
            </div>
            
            <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Fornitori</h3>
                <p class="text-3xl font-bold">0</p>
                <p class="text-sm mt-2">Attivi: 0</p>
            </div>
            
            <div class="bg-purple-100 dark:bg-purple-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Prodotti</h3>
                <p class="text-3xl font-bold">0</p>
                <p class="text-sm mt-2">Categorie: 0</p>
            </div>
        </div>

        <!-- Company Management -->
        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 mb-8">
            <h3 class="text-xl font-semibold mb-4">Gestione Utenti</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruolo</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($latestUsers as $user)
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Attivo
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inattivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifica</a>
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        {{ $user->is_active ? 'Disattiva' : 'Attiva' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions and Reports -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.ddt.create') }}" class="block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500">
                        Nuovo DDT
                    </a>
                    <a href="{{ route('admin.users.create') }}" class="block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">
                        Nuovo Utente
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="block px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-500">
                        Nuovo Prodotto
                    </a>
                    <a href="{{ route('admin.suppliers.create') }}" class="block px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-500">
                        Nuovo Fornitore
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Reports</h3>
                <div class="space-y-2">
                    <a href="#" class="block px-4 py-2 bg-gray-100 dark:bg-gray-600 rounded hover:bg-gray-200 dark:hover:bg-gray-500">
                        Report DDT
                    </a>
                    <a href="#" class="block px-4 py-2 bg-gray-100 dark:bg-gray-600 rounded hover:bg-gray-200 dark:hover:bg-gray-500">
                        Report Produzione
                    </a>
                    <a href="#" class="block px-4 py-2 bg-gray-100 dark:bg-gray-600 rounded hover:bg-gray-200 dark:hover:bg-gray-500">
                        Report Magazzino
                    </a>
                    <a href="#" class="block px-4 py-2 bg-gray-100 dark:bg-gray-600 rounded hover:bg-gray-200 dark:hover:bg-gray-500">
                        Report Attività
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
