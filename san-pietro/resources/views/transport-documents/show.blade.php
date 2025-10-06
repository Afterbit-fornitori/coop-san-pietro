@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">
                        Dettaglio {{ $transportDocument->tipo_documento }} - {{ $transportDocument->numero_completo }}
                    </h2>
                    <div class="flex gap-2">
                        <a href="{{ route('transport-documents.pdf.view', $transportDocument) }}" target="_blank"
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            📄 Visualizza PDF
                        </a>
                        <a href="{{ route('transport-documents.pdf.download', $transportDocument) }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            ⬇️ Scarica PDF
                        </a>
                        <a href="{{ route('transport-documents.edit', $transportDocument) }}"
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            ✏️ Modifica
                        </a>
                        <a href="{{ route('transport-documents.index') }}"
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            ← Indietro
                        </a>
                    </div>
                </div>

                <!-- Informazioni Documento -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Colonna Sinistra: Info Documento -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informazioni Documento</h3>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-700">Tipo:</dt>
                                <dd>
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                        {{ $transportDocument->tipo_documento }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-700">Numero Completo:</dt>
                                <dd class="font-bold">{{ $transportDocument->numero_completo }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-700">Serie:</dt>
                                <dd>{{ $transportDocument->serie }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-700">Numero:</dt>
                                <dd>{{ $transportDocument->numero }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-700">Anno:</dt>
                                <dd>{{ $transportDocument->anno }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-700">Data Documento:</dt>
                                <dd>{{ $transportDocument->data_documento?->format('d/m/Y') ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-700">Ora Partenza:</dt>
                                <dd>{{ $transportDocument->ora_partenza?->format('H:i') ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-700">Data Raccolta:</dt>
                                <dd>{{ $transportDocument->data_raccolta?->format('d/m/Y') ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Colonna Destra: Info Cliente e Trasporto -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Cliente e Trasporto</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="font-medium text-gray-700 mb-1">Cliente:</dt>
                                <dd class="ml-2">
                                    <div class="font-semibold">{{ $transportDocument->client->business_name ?? 'N/A' }}</div>
                                    @if($transportDocument->client)
                                        <div class="text-sm text-gray-600">
                                            {{ $transportDocument->client->address ?? '' }}
                                            @if($transportDocument->client->city)
                                                - {{ $transportDocument->client->city }}
                                            @endif
                                        </div>
                                        @if($transportDocument->client->vat_number)
                                            <div class="text-sm text-gray-600">P.IVA: {{ $transportDocument->client->vat_number }}</div>
                                        @endif
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-700">Causale Trasporto:</dt>
                                <dd>{{ $transportDocument->causale_trasporto ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-700">Mezzo Trasporto:</dt>
                                <dd>{{ $transportDocument->mezzo_trasporto ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-700">Zona Produzione:</dt>
                                <dd>
                                    @if($transportDocument->productionZone)
                                        {{ $transportDocument->productionZone->codice }} - {{ $transportDocument->productionZone->nome }}
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-700">Socio Raccoglitore:</dt>
                                <dd>
                                    @if($transportDocument->member)
                                        {{ $transportDocument->member->last_name }} {{ $transportDocument->member->first_name }}
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Annotazioni -->
                @if($transportDocument->annotazioni)
                    <div class="bg-yellow-50 p-4 rounded-lg mb-8">
                        <h3 class="text-lg font-semibold mb-2">Annotazioni</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $transportDocument->annotazioni }}</p>
                    </div>
                @endif

                <!-- Tabella Prodotti -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Prodotti</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prodotto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Codice</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantità (kg)</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">N. Colli</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Prezzo €/kg</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Totale €</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($transportDocument->items as $item)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="font-medium">{{ $item->product->nome_completo ?? 'N/A' }}</div>
                                            @if($item->product)
                                                <div class="text-sm text-gray-500">{{ $item->product->nome_scientifico ?? '' }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $item->product->codice ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-right">{{ number_format($item->quantita_kg, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right">{{ $item->numero_colli }}</td>
                                        <td class="px-6 py-4 text-right">{{ number_format($item->prezzo_unitario, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-right font-semibold">{{ number_format($item->totale, 2, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Nessun prodotto associato a questo documento.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Totali -->
                <div class="bg-blue-50 p-6 rounded-lg">
                    <div class="max-w-md ml-auto">
                        <dl class="space-y-3">
                            <div class="flex justify-between text-lg">
                                <dt class="font-semibold">Totale Kg:</dt>
                                <dd class="font-bold">{{ number_format($transportDocument->items->sum('quantita_kg'), 2, ',', '.') }} kg</dd>
                            </div>
                            <div class="flex justify-between text-lg">
                                <dt class="font-semibold">Totale Colli:</dt>
                                <dd class="font-bold">{{ $transportDocument->items->sum('numero_colli') }}</dd>
                            </div>
                            <div class="border-t border-blue-200 pt-3"></div>
                            <div class="flex justify-between text-lg">
                                <dt class="font-semibold">Totale Imponibile:</dt>
                                <dd class="font-bold text-xl">€ {{ number_format($transportDocument->totale_imponibile ?? 0, 2, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-700">IVA (10%):</dt>
                                <dd class="font-semibold">€ {{ number_format(($transportDocument->totale_imponibile ?? 0) * 0.10, 2, ',', '.') }}</dd>
                            </div>
                            <div class="border-t-2 border-blue-300 pt-3"></div>
                            <div class="flex justify-between text-xl">
                                <dt class="font-bold">Totale Documento:</dt>
                                <dd class="font-bold text-blue-600">€ {{ number_format(($transportDocument->totale_imponibile ?? 0) * 1.10, 2, ',', '.') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Azioni di Gestione -->
                <div class="mt-8 flex justify-between items-center border-t pt-6">
                    <form action="{{ route('transport-documents.destroy', $transportDocument) }}" method="POST"
                          onsubmit="return confirm('Sei sicuro di voler eliminare questo documento? Questa azione non può essere annullata.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            🗑️ Elimina Documento
                        </button>
                    </form>

                    <div class="flex gap-2">
                        <a href="{{ route('transport-documents.edit', $transportDocument) }}"
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            ✏️ Modifica
                        </a>
                        <a href="{{ route('transport-documents.index') }}"
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            ← Torna all'elenco
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
