@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Dettagli Registrazione Carico/Scarico</h2>
            <div class="flex space-x-3">
                <a href="{{ route('loading-unloading.edit', $loadingUnloadingRegister) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Modifica
                </a>
                <a href="{{ route('loading-unloading.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Torna alla Lista
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informazioni Base -->
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informazioni Base</h3>

                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Data Operazione</label>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($loadingUnloadingRegister->data_operazione)->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Tipo Operazione</label>
                        <p>
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full
                                {{ $loadingUnloadingRegister->tipo_operazione === 'CARICO' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $loadingUnloadingRegister->tipo_operazione }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Lotto</label>
                        <p class="font-mono font-semibold">{{ $loadingUnloadingRegister->lotto ?? 'Non specificato' }}</p>
                    </div>
                </div>
            </div>

            <!-- Prodotto -->
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Prodotto</h3>

                @if($loadingUnloadingRegister->product)
                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Codice</label>
                        <p class="font-mono font-semibold">{{ $loadingUnloadingRegister->product->codice }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Nome Commerciale</label>
                        <p class="font-semibold">{{ $loadingUnloadingRegister->product->nome_commerciale }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Nome Scientifico</label>
                        <p class="italic text-gray-600 dark:text-gray-300">{{ $loadingUnloadingRegister->product->nome_scientifico }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Specie</label>
                        <p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($loadingUnloadingRegister->product->specie === 'VONGOLE') bg-blue-100 text-blue-800
                                @elseif($loadingUnloadingRegister->product->specie === 'COZZE') bg-purple-100 text-purple-800
                                @elseif($loadingUnloadingRegister->product->specie === 'OSTRICHE') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $loadingUnloadingRegister->product->specie }}
                            </span>
                        </p>
                    </div>
                </div>
                @else
                <p class="text-gray-400">Prodotto non specificato</p>
                @endif
            </div>

            <!-- Quantità per Categoria -->
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow md:col-span-2">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Quantità per Categoria</h3>

                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="text-center p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                        <label class="text-sm text-gray-600 dark:text-gray-300">Reimmersione</label>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($loadingUnloadingRegister->kg_reimmersione, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500">kg</p>
                    </div>

                    <div class="text-center p-4 bg-green-50 dark:bg-green-900 rounded-lg">
                        <label class="text-sm text-gray-600 dark:text-gray-300">Piccola</label>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ number_format($loadingUnloadingRegister->kg_piccola, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500">kg</p>
                    </div>

                    <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900 rounded-lg">
                        <label class="text-sm text-gray-600 dark:text-gray-300">Media</label>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                            {{ number_format($loadingUnloadingRegister->kg_media, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500">kg</p>
                    </div>

                    <div class="text-center p-4 bg-orange-50 dark:bg-orange-900 rounded-lg">
                        <label class="text-sm text-gray-600 dark:text-gray-300">Grossa</label>
                        <p class="text-2xl font-bold text-orange-600 dark:text-orange-400">
                            {{ number_format($loadingUnloadingRegister->kg_grossa, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500">kg</p>
                    </div>

                    <div class="text-center p-4 bg-red-50 dark:bg-red-900 rounded-lg">
                        <label class="text-sm text-gray-600 dark:text-gray-300">Granchio</label>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                            {{ number_format($loadingUnloadingRegister->kg_granchio, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500">kg</p>
                    </div>
                </div>

                @php
                    $totale = $loadingUnloadingRegister->kg_reimmersione +
                              $loadingUnloadingRegister->kg_piccola +
                              $loadingUnloadingRegister->kg_media +
                              $loadingUnloadingRegister->kg_grossa +
                              $loadingUnloadingRegister->kg_granchio;
                @endphp

                <div class="mt-4 pt-4 border-t border-gray-300 dark:border-gray-600 text-center">
                    <label class="text-sm text-gray-500 dark:text-gray-400">Totale Complessivo</label>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                        {{ number_format($totale, 2, ',', '.') }} kg
                    </p>
                </div>
            </div>

            <!-- Provenienza/Destinazione -->
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Provenienza/Destinazione</h3>

                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Descrizione</label>
                        <p>{{ $loadingUnloadingRegister->provenienza_destinazione ?? 'Non specificato' }}</p>
                    </div>

                    @if($loadingUnloadingRegister->transportDocument)
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Documento di Trasporto</label>
                        <p>
                            <a href="{{ route('documents.show', $loadingUnloadingRegister->transportDocument) }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                                {{ $loadingUnloadingRegister->transportDocument->serie }}{{ $loadingUnloadingRegister->transportDocument->numero }}/{{ $loadingUnloadingRegister->transportDocument->anno }}
                            </a>
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $loadingUnloadingRegister->transportDocument->data_documento->format('d/m/Y') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Note -->
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Note</h3>

                <div>
                    @if($loadingUnloadingRegister->note)
                        <p class="text-gray-600 dark:text-gray-300">{{ $loadingUnloadingRegister->note }}</p>
                    @else
                        <p class="text-gray-400 italic">Nessuna nota</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Azioni -->
        <div class="mt-6 pt-4 border-t border-gray-300 dark:border-gray-600 flex justify-between">
            <form action="{{ route('loading-unloading.destroy', $loadingUnloadingRegister) }}" method="POST"
                  onsubmit="return confirm('Sei sicuro di voler eliminare questa registrazione?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Elimina Registrazione
                </button>
            </form>

            <div class="flex space-x-3">
                <a href="{{ route('loading-unloading.edit', $loadingUnloadingRegister) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Modifica Registrazione
                </a>
                <a href="{{ route('loading-unloading.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Torna alla Lista
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
