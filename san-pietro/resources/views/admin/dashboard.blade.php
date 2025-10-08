@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Dashboard Super Admin</h2>

                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Companies Stats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                        <div class="text-gray-900 font-semibold text-lg mb-2">Aziende</div>
                        <div class="text-3xl font-bold text-blue-600">{{ $companiesCount }}</div>
                        <div class="text-sm text-gray-500">Aziende registrate</div>
                    </div>

                    <!-- Users Stats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                        <div class="text-gray-900 font-semibold text-lg mb-2">Utenti</div>
                        <div class="text-3xl font-bold text-green-600">{{ $usersCount }}</div>
                        <div class="text-sm text-gray-500">Utenti totali</div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                        <div class="text-gray-900 font-semibold text-lg mb-2">Attività Recenti</div>
                        <div class="text-3xl font-bold text-purple-600">{{ $recentActivities }}</div>
                        <div class="text-sm text-gray-500">Attività nelle ultime 24 ore</div>
                    </div>

                    <!-- DDT Totali -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                        <div class="text-gray-900 font-semibold text-lg mb-2">DDT Totali</div>
                        <div class="text-3xl font-bold text-indigo-600">{{ $transportDocumentsCount }}</div>
                        <div class="text-sm text-gray-500">Documenti di trasporto</div>
                    </div>

                    <!-- Clienti -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                        <div class="text-gray-900 font-semibold text-lg mb-2">Clienti</div>
                        <div class="text-3xl font-bold text-amber-600">{{ $clientsCount }}</div>
                        <div class="text-sm text-gray-500">Clienti registrati</div>
                    </div>

                    <!-- Prodotti -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                        <div class="text-gray-900 font-semibold text-lg mb-2">Prodotti</div>
                        <div class="text-3xl font-bold text-cyan-600">{{ $productsCount }}</div>
                        <div class="text-sm text-gray-500">Prodotti catalogati</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Azioni Rapide</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <a href="{{ route('admin.companies.index') }}"
                            class="inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-medium transition duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H3m2 0h3M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Gestisci Aziende
                        </a>

                        <a href="{{ route('admin.companies.create') }}"
                            class="inline-flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-medium transition duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Nuova Azienda
                        </a>

                        <a href="{{ route('company.invitations.index') }}"
                            class="inline-flex items-center justify-center px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-md font-medium transition duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Gestisci Inviti
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium transition duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            Gestisci Utenti
                        </a>

                        <a href="{{ route('admin.users.create') }}"
                            class="inline-flex items-center justify-center px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md font-medium transition duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Nuovo Utente
                        </a>
                    </div>
                </div>

                <!-- Recent Companies -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aziende Recenti</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Creazione</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentCompanies as $company)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $company->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $company->type }}</div>
                                        </td>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $company->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('admin.companies.show', $company) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Visualizza</a>
                                            <a href="{{ route('admin.companies.edit', $company) }}" class="text-blue-600 hover:text-blue-900">Modifica</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection