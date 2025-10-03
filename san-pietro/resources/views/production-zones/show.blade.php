@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Dettagli Zona: {{ $zone->nome }}</h2>
            <div class="flex space-x-3">
                <a href="{{ route('production-zones.edit', $zone) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Modifica
                </a>
                <a href="{{ route('production-zones.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
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
                        <label class="text-sm text-gray-500 dark:text-gray-400">Codice Ministeriale</label>
                        <p class="font-mono font-semibold">{{ $zone->codice }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Nome Zona</label>
                        <p class="font-semibold text-lg">{{ $zone->nome }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Superficie</label>
                        @if($zone->mq)
                            <p class="font-semibold">{{ number_format($zone->mq, 0, ',', '.') }} m²</p>
                        @else
                            <p class="text-gray-400">Non specificato</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Classificazione Sanitaria -->
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Classificazione Sanitaria</h3>

                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Classe Sanitaria</label>
                        @if($zone->classe_sanitaria)
                            <p>
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full
                                    @if($zone->classe_sanitaria === 'A') bg-green-100 text-green-800
                                    @elseif($zone->classe_sanitaria === 'B') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    Classe {{ $zone->classe_sanitaria }}
                                </span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($zone->classe_sanitaria === 'A')
                                    Idonea al consumo umano diretto
                                @elseif($zone->classe_sanitaria === 'B')
                                    Richiede depurazione
                                @else
                                    Richiede reimmersione
                                @endif
                            </p>
                        @else
                            <p class="text-gray-400">Non specificata</p>
                        @endif
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Declassificazione Temporanea</label>
                        @if($zone->declassificazione_temporanea)
                            <p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Sì, Declassificata
                                </span>
                            </p>
                        @else
                            <p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    No
                                </span>
                            </p>
                        @endif
                    </div>

                    @if($zone->declassificazione_temporanea && $zone->data_declassificazione)
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Data Declassificazione</label>
                        <p class="font-semibold">{{ \Carbon\Carbon::parse($zone->data_declassificazione)->format('d/m/Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Stato -->
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Stato</h3>

                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Stato Zona</label>
                        <p>
                            @if($zone->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Attiva
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inattiva
                                </span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Idonea per Consumo Diretto</label>
                        @if($zone->canProduceForConsumption())
                            <p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Sì
                                </span>
                            </p>
                        @else
                            <p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    No
                                </span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                @if($zone->declassificazione_temporanea)
                                    Zona temporaneamente declassificata
                                @elseif($zone->classe_sanitaria !== 'A')
                                    Classe sanitaria non idonea al consumo diretto
                                @else
                                    Zona non attiva
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Date -->
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4 border-b pb-2">Date</h3>

                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Data Creazione</label>
                        <p>{{ $zone->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500 dark:text-gray-400">Ultimo Aggiornamento</label>
                        <p>{{ $zone->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Azioni -->
        <div class="mt-6 pt-4 border-t border-gray-300 dark:border-gray-600 flex justify-between">
            <form action="{{ route('production-zones.destroy', $zone) }}" method="POST"
                  onsubmit="return confirm('Sei sicuro di voler eliminare questa zona di produzione?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Elimina Zona
                </button>
            </form>

            <div class="flex space-x-3">
                <a href="{{ route('production-zones.edit', $zone) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Modifica Zona
                </a>
                <a href="{{ route('production-zones.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Torna alla Lista
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
