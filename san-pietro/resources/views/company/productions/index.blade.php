@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Gestione Produzioni</h2>
            <a href="{{ route('production.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Nuova Produzione
            </a>
        </div>

        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-300 rounded">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Socio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Settimana</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Micro</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Standard</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Totale</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($productions as $production)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium">{{ $production->member->full_name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $production->member->rpm_registration ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium">Settimana {{ $production->week_number }}</div>
                                <div class="text-sm text-gray-500">{{ $production->year }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">{{ number_format($production->micro_quantity, 2) }} kg</div>
                                <div class="text-xs text-gray-500">€{{ number_format($production->micro_price, 2) }}/kg</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">{{ number_format($production->standard_quantity, 2) }} kg</div>
                                <div class="text-xs text-gray-500">€{{ number_format($production->standard_price, 2) }}/kg</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium">{{ number_format($production->total_quantity, 2) }} kg</div>
                                <div class="text-sm text-green-600">€{{ number_format($production->total_amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('production.edit', $production) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Modifica
                                </a>
                                <form action="{{ route('production.destroy', $production) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Sei sicuro di voler eliminare questa produzione?')">
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
</div>
@endsection