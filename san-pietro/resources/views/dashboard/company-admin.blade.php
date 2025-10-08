@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Company Metrics -->
            <div class="bg-blue-100 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Utenti</h3>
                <p class="text-3xl font-bold">{{ $usersCount }}</p>
                <p class="text-sm mt-2">Attivi: {{ $activeUsersCount }}</p>
            </div>

            <div class="bg-green-100 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">DDT Totali</h3>
                <p class="text-3xl font-bold">{{ $transportDocumentsCount }}</p>
                <p class="text-sm mt-2">In attesa: 0</p>
            </div>

            <div class="bg-yellow-100 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Clienti</h3>
                <p class="text-3xl font-bold">{{ $clientsCount }}</p>
                <p class="text-sm mt-2">Attivi: 0</p>
            </div>

            <div class="bg-purple-100 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Prodotti</h3>
                <p class="text-3xl font-bold">{{ $productsCount }}</p>
                <p class="text-sm mt-2">Categorie: 0</p>
            </div>
        </div>

        <!-- Company Management -->
        <div class="bg-white shadow rounded-lg p-4 mb-8">
            <h3 class="text-xl font-semibold mb-4">Gestione Utenti</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruolo</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
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
                                <a href="{{ route('company.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifica</a>
                                <form action="{{ route('company.users.toggle-status', $user) }}" method="POST" class="inline">
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
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    @if(auth()->user()->hasRole('COMPANY_ADMIN') && auth()->user()->company && auth()->user()->company->isMain())
                    <a href="{{ route('company.companies.create') }}" class="block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">
                        Nuova Azienda
                    </a>
                    @endif
                    <a href="{{ route('company.users.create') }}" class="block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">
                        Nuovo Utente
                    </a>
                    <a href="{{ route('members.create') }}" class="block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500">
                        Nuovo Socio
                    </a>
                    <a href="{{ route('production.create') }}" class="block px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-500">
                        Nuova Produzione
                    </a>
                    <a href="{{ route('documents.create') }}" class="block px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-500">
                        Nuovo DDT
                    </a>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Reports</h3>
                <div class="space-y-2">
                    <a href="#" class="block px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
                        Report DDT
                    </a>
                    <a href="#" class="block px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
                        Report Produzione
                    </a>
                    <a href="#" class="block px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
                        Report Magazzino
                    </a>
                    <a href="#" class="block px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
                        Report Attività
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection