@extends('layouts.app')

@section('title', 'Dashboard Operativa')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <h1 class="text-2xl font-bold mb-6">Dashboard Operativa - {{ auth()->user()->company->name }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Metrics Operativi -->
            <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Soci Gestiti</h3>
                <p class="text-3xl font-bold">{{ auth()->user()->company->members()->count() }}</p>
            </div>

            <div class="bg-green-100 dark:bg-green-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">DDT questo Mese</h3>
                <p class="text-3xl font-bold">{{ auth()->user()->company->transportDocuments()->whereMonth('created_at', now()->month)->count() }}</p>
            </div>

            <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Clienti Attivi</h3>
                <p class="text-3xl font-bold">{{ auth()->user()->company->clients()->count() }}</p>
            </div>

            <div class="bg-purple-100 dark:bg-purple-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Record Settimanali</h3>
                <p class="text-3xl font-bold">{{ auth()->user()->company->weeklyRecords()->whereMonth('created_at', now()->month)->count() }}</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Azioni Rapide</h3>
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('members.index') }}" class="flex items-center justify-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Gestisci Soci
                    </a>

                    <a href="{{ route('weekly-records.index') }}" class="flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Record Settimanali
                    </a>

                    <a href="{{ route('documents.create') }}" class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Nuovo DDT
                    </a>

                    <a href="{{ route('clients.index') }}" class="flex items-center justify-center px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Gestisci Clienti
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Attività Recenti</h3>
                <div class="space-y-3">
                    @if(auth()->user()->company->transportDocuments()->latest()->limit(5)->exists())
                        @foreach(auth()->user()->company->transportDocuments()->latest()->limit(5)->get() as $document)
                        <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-sm font-medium">DDT #{{ $document->numero }}/{{ $document->anno }}</h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        Cliente: {{ $document->client->ragione_sociale ?? 'N/A' }}
                                    </p>
                                </div>
                                <span class="text-xs text-gray-500">{{ $document->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Nessuna attività recente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection