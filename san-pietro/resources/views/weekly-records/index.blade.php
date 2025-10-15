@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Registri Settimanali</h2>
            <a href="{{ route('weekly-records.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Nuovo Registro
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periodo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reimmersione</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consumo Diretto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Totali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                        @forelse($weeklyRecords as $record)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium">{{ $record->member->full_name }}</div>
                                <div class="text-sm text-gray-500">{{ $record->member->rpm_registration }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium">Settimana {{ $record->week }}</div>
                                <div class="text-sm text-gray-500">Anno {{ $record->year }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    Micro: {{ number_format($record->kg_micro_internal_reimmersion, 2) }} kg
                                </div>
                                <div class="text-sm">
                                    Piccola: {{ number_format($record->kg_small_internal_reimmersion, 2) }} kg
                                </div>
                                <div class="text-xs text-gray-500">
                                    €{{ number_format($record->total_micro_internal + $record->total_small_internal, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">
                                    Tot: {{ number_format($record->kg_medium_consumption + $record->kg_large_consumption + $record->kg_super_consumption, 2) }} kg
                                </div>
                                <div class="text-xs text-gray-500">
                                    €{{ number_format($record->total_medium + $record->total_large + $record->total_super, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-green-600">
                                    {{ number_format($record->kg_micro_internal_reimmersion + $record->kg_small_internal_reimmersion + $record->kg_medium_consumption + $record->kg_large_consumption + $record->kg_super_consumption, 2) }} kg
                                </div>
                                <div class="font-medium text-green-600">
                                    €{{ number_format($record->total_micro_internal + $record->total_small_internal + $record->total_medium + $record->total_large + $record->total_super, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('weekly-records.show', $record) }}" class="text-green-600 hover:text-green-900 mr-3">
                                    Dettagli
                                </a>
                                <a href="{{ route('weekly-records.edit', $record) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Modifica
                                </a>
                                <form action="{{ route('weekly-records.destroy', $record) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Sei sicuro di voler eliminare questo registro?')">
                                        Elimina
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Nessun registro trovato. <a href="{{ route('weekly-records.create') }}" class="text-blue-600 hover:text-blue-800">Creane uno nuovo</a>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection