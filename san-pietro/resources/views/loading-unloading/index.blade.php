@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Registro Carico/Scarico</h2>
            <a href="{{ route('loading-unloading.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Nuova Registrazione
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodotto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lotto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantità (kg)</th>
                            <!-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DDT</th> -->
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                        @forelse($registers as $register)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium">{{ \Carbon\Carbon::parse($register->data_operazione)->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $register->tipo_operazione === 'CARICO' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $register->tipo_operazione }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $register->product->nome_commerciale ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $register->product->codice ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm">{{ $register->lotto ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm space-y-1">
                                    @if($register->kg_reimmersione > 0)
                                    <div><span class="text-gray-500">Reim:</span> {{ number_format($register->kg_reimmersione, 2, ',', '.') }} kg</div>
                                    @endif
                                    @if($register->kg_piccola > 0)
                                    <div><span class="text-gray-500">Piccola:</span> {{ number_format($register->kg_piccola, 2, ',', '.') }} kg</div>
                                    @endif
                                    @if($register->kg_media > 0)
                                    <div><span class="text-gray-500">Media:</span> {{ number_format($register->kg_media, 2, ',', '.') }} kg</div>
                                    @endif
                                    @if($register->kg_grossa > 0)
                                    <div><span class="text-gray-500">Grossa:</span> {{ number_format($register->kg_grossa, 2, ',', '.') }} kg</div>
                                    @endif
                                    @if($register->kg_granchio > 0)
                                    <div><span class="text-gray-500">Granchio:</span> {{ number_format($register->kg_granchio, 2, ',', '.') }} kg</div>
                                    @endif
                                    @php
                                    $totale = $register->kg_reimmersione + $register->kg_piccola + $register->kg_media + $register->kg_grossa + $register->kg_granchio;
                                    @endphp
                                    @if($totale > 0)
                                    <div class="font-semibold border-t pt-1"><span class="text-gray-500">Totale:</span> {{ number_format($totale, 2, ',', '.') }} kg</div>
                                    @endif
                                </div>
                            </td>
                            <!-- <td class="px-6 py-4 whitespace-nowrap">
                                @if($register->transportDocument)
                                <a href="{{ route('transport-documents.show', $register->transportDocument) }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $register->transportDocument->numero }}
                                </a>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td> -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('loading-unloading.show', $register) }}" class="text-green-600 hover:text-green-900 mr-3">
                                    Dettagli
                                </a>
                                <a href="{{ route('loading-unloading.edit', $register) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Modifica
                                </a>
                                <form action="{{ route('loading-unloading.destroy', $register) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Sei sicuro di voler eliminare questa registrazione?')">
                                        Elimina
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Nessuna registrazione trovata. <a href="{{ route('loading-unloading.create') }}" class="text-blue-600 hover:text-blue-800">Creane una nuova</a>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($registers->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                {{ $registers->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection