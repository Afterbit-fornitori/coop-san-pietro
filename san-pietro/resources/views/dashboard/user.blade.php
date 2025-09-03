@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <!-- User Metrics -->
            <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">DDT Creati</h3>
                <p class="text-3xl font-bold">0</p>
            </div>

            <div class="bg-green-100 dark:bg-green-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Prodotti Gestiti</h3>
                <p class="text-3xl font-bold">0</p>
            </div>

            <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">Attività Recenti</h3>
                <p class="text-3xl font-bold">0</p>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 mb-8">
            <h3 class="text-xl font-semibold mb-4">Attività Recenti</h3>
            <div class="space-y-4">
                @foreach(range(1, 5) as $i)
                <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-lg font-medium">Attività {{ $i }}</h4>
                            <p class="text-gray-600 dark:text-gray-400">Descrizione dell'attività...</p>
                        </div>
                        <span class="text-sm text-gray-500">2 ore fa</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Azioni Rapide</h3>
                <div class="space-y-2">
                    <a href="{{ route('documents.create') }}" class="block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500">
                        Nuovo DDT
                    </a>
                    <a href="{{ route('production.index') }}" class="block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">
                        Visualizza Produzioni
                    </a>
                    <a href="{{ route('members.index') }}" class="block px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-500">
                        Visualizza Soci
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">DDT Recenti</h3>
                <div class="space-y-2">
                    @foreach(range(1, 4) as $i)
                    <div class="p-2 bg-gray-50 dark:bg-gray-800 rounded flex justify-between items-center">
                        <div>
                            <p class="font-medium">DDT #{{ $i }}</p>
                            <span class="text-sm text-gray-500">Cliente XYZ</span>
                        </div>
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Dettagli</a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection