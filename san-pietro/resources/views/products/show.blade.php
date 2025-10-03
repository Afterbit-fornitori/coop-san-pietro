@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Dettagli Prodotto: {{ $product->nome_commerciale }}</h2>
            <div class="flex space-x-3">
                <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Modifica
                </a>
                <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
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
                        <label class="text-sm text-gray-500 dark:text-gray-400">Codice Prodotto</label>
                        <p class="font-mono font-semibold">{{ $product->codice }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Nome Commerciale</label>
                        <p class="font-semibold">{{ $product->nome_commerciale }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Nome Scientifico</label>
                        <p class="italic text-gray-600 dark:text-gray-300">{{ $product->nome_scientifico }}</p>
                    </div>
                </div>
            </div>

            <!-- Classificazione -->
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Classificazione</h3>

                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Specie</label>
                        <p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($product->specie === 'VONGOLE') bg-blue-100 text-blue-800
                                @elseif($product->specie === 'COZZE') bg-purple-100 text-purple-800
                                @elseif($product->specie === 'OSTRICHE') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ $product->specie }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Pezzatura</label>
                        <p class="font-semibold">{{ $product->pezzatura }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Destinazione</label>
                        <p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($product->destinazione === 'CONSUMO') bg-green-100 text-green-800
                                @elseif($product->destinazione === 'REIMMERSIONE') bg-orange-100 text-orange-800
                                @else bg-cyan-100 text-cyan-800 @endif">
                                {{ $product->destinazione }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Prezzo e Unità -->
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Prezzo e Unità</h3>

                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Prezzo Base</label>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            € {{ number_format($product->prezzo_base, 2, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Unità di Misura</label>
                        <p class="font-semibold">{{ strtoupper($product->unita_misura) }}</p>
                    </div>
                </div>
            </div>

            <!-- Stato e Date -->
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Stato e Date</h3>

                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Stato</label>
                        <p>
                            @if($product->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Attivo
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inattivo
                                </span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Data Creazione</label>
                        <p>{{ $product->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Ultimo Aggiornamento</label>
                        <p>{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Azioni -->
        <div class="mt-6 pt-4 border-t border-gray-300 dark:border-gray-600 flex justify-between">
            <form action="{{ route('products.destroy', $product) }}" method="POST"
                  onsubmit="return confirm('Sei sicuro di voler eliminare questo prodotto?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Elimina Prodotto
                </button>
            </form>

            <div class="flex space-x-3">
                <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Modifica Prodotto
                </a>
                <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Torna alla Lista
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
