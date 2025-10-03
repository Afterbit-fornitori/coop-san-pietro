@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Zone di Produzione</h2>
            <a href="{{ route('production-zones.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Nuova Zona
            </a>
        </div>

        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-300 rounded">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Codice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MQ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classe Sanitaria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Declassificazione</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                        @forelse($zones as $zone)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-semibold">{{ $zone->codice }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $zone->nome }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($zone->mq)
                                    <span class="text-sm">{{ number_format($zone->mq, 0, ',', '.') }} m²</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($zone->classe_sanitaria)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($zone->classe_sanitaria === 'A') bg-green-100 text-green-800
                                        @elseif($zone->classe_sanitaria === 'B') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        Classe {{ $zone->classe_sanitaria }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($zone->declassificazione_temporanea)
                                    <div>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                            Declassificata
                                        </span>
                                        @if($zone->data_declassificazione)
                                            <div class="text-xs text-gray-500 mt-1">Dal {{ \Carbon\Carbon::parse($zone->data_declassificazione)->format('d/m/Y') }}</div>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($zone->is_active)
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
                                <a href="{{ route('production-zones.show', $zone) }}" class="text-green-600 hover:text-green-900 mr-3">
                                    Dettagli
                                </a>
                                <a href="{{ route('production-zones.edit', $zone) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Modifica
                                </a>
                                <form action="{{ route('production-zones.destroy', $zone) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Sei sicuro di voler eliminare questa zona?')">
                                        Elimina
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Nessuna zona di produzione trovata. <a href="{{ route('production-zones.create') }}" class="text-blue-600 hover:text-blue-800">Creane una nuova</a>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($zones->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                {{ $zones->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
