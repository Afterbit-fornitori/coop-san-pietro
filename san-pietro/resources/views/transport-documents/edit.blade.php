@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">
                        Modifica Documento di Trasporto {{ $transportDocument->numero_completo }}
                    </h2>
                    <a href="{{ route('transport-documents.index') }}"
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        ← Indietro
                    </a>
                </div>

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('transport-documents.update', $transportDocument) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Tipo Documento -->
                        <div>
                            <label for="tipo_documento" class="block text-sm font-medium text-gray-700">Tipo Documento *</label>
                            <select name="tipo_documento" id="tipo_documento"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                <option value="DDT" {{ old('tipo_documento', $transportDocument->tipo_documento) == 'DDT' ? 'selected' : '' }}>DDT - Documento Di Trasporto</option>
                                <option value="DTN" {{ old('tipo_documento', $transportDocument->tipo_documento) == 'DTN' ? 'selected' : '' }}>DTN - Documento Trasporto Nazionale</option>
                                <option value="DDR" {{ old('tipo_documento', $transportDocument->tipo_documento) == 'DDR' ? 'selected' : '' }}>DDR - Documento Di Reso</option>
                            </select>
                        </div>

                        <!-- Serie (readonly) -->
                        <div>
                            <label for="serie" class="block text-sm font-medium text-gray-700">Serie</label>
                            <input type="text" name="serie" id="serie"
                                   value="{{ $transportDocument->serie }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed"
                                   readonly>
                            <p class="mt-1 text-xs text-gray-500">La serie non può essere modificata</p>
                        </div>

                        <!-- Numero (readonly) -->
                        <div>
                            <label for="numero" class="block text-sm font-medium text-gray-700">Numero</label>
                            <input type="text" name="numero" id="numero"
                                   value="{{ $transportDocument->numero }}/{{ $transportDocument->anno }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 cursor-not-allowed"
                                   readonly>
                            <p class="mt-1 text-xs text-gray-500">Il numero progressivo non può essere modificato</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Data Documento -->
                        <div>
                            <label for="data_documento" class="block text-sm font-medium text-gray-700">Data Documento *</label>
                            <input type="date" name="data_documento" id="data_documento"
                                   value="{{ old('data_documento', $transportDocument->data_documento?->format('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   required>
                        </div>

                        <!-- Ora Partenza -->
                        <div>
                            <label for="ora_partenza" class="block text-sm font-medium text-gray-700">Ora Partenza</label>
                            <input type="time" name="ora_partenza" id="ora_partenza"
                                   value="{{ old('ora_partenza', $transportDocument->ora_partenza?->format('H:i')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Data Raccolta -->
                        <div>
                            <label for="data_raccolta" class="block text-sm font-medium text-gray-700">Data Raccolta</label>
                            <input type="date" name="data_raccolta" id="data_raccolta"
                                   value="{{ old('data_raccolta', $transportDocument->data_raccolta?->format('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Cliente -->
                    <div class="mb-6">
                        <label for="client_id" class="block text-sm font-medium text-gray-700">Cliente *</label>
                        <select name="client_id" id="client_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            <option value="">-- Seleziona Cliente --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                        {{ old('client_id', $transportDocument->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->business_name }} - {{ $client->city ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Causale Trasporto -->
                        <div>
                            <label for="causale_trasporto" class="block text-sm font-medium text-gray-700">Causale Trasporto *</label>
                            <input type="text" name="causale_trasporto" id="causale_trasporto"
                                   value="{{ old('causale_trasporto', $transportDocument->causale_trasporto) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="es. Vendita, Conto Vendita, Conto Visione"
                                   required>
                        </div>

                        <!-- Mezzo Trasporto -->
                        <div>
                            <label for="mezzo_trasporto" class="block text-sm font-medium text-gray-700">Mezzo Trasporto</label>
                            <input type="text" name="mezzo_trasporto" id="mezzo_trasporto"
                                   value="{{ old('mezzo_trasporto', $transportDocument->mezzo_trasporto) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="es. Autocarro, Furgone">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Zona Produzione -->
                        <div>
                            <label for="production_zone_id" class="block text-sm font-medium text-gray-700">Zona Produzione</label>
                            <select name="production_zone_id" id="production_zone_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Seleziona Zona --</option>
                                @foreach($productionZones as $zone)
                                    <option value="{{ $zone->id }}"
                                            {{ old('production_zone_id', $transportDocument->production_zone_id) == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->codice }} - {{ $zone->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Socio Raccoglitore -->
                        <div>
                            <label for="member_id" class="block text-sm font-medium text-gray-700">Socio Raccoglitore</label>
                            <select name="member_id" id="member_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Seleziona Socio --</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}"
                                            {{ old('member_id', $transportDocument->member_id) == $member->id ? 'selected' : '' }}>
                                        {{ $member->last_name }} {{ $member->first_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Annotazioni -->
                    <div class="mb-6">
                        <label for="annotazioni" class="block text-sm font-medium text-gray-700">Annotazioni</label>
                        <textarea name="annotazioni" id="annotazioni" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Note aggiuntive sul trasporto">{{ old('annotazioni', $transportDocument->annotazioni) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('transport-documents.index') }}"
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Annulla
                        </a>
                        <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Salva Modifiche
                        </button>
                    </div>
                </form>

                <!-- Sezione Gestione Prodotti (Items) -->
                <div class="mt-10 border-t pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Prodotti del Documento</h3>
                        <button type="button" id="addItemBtn"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            + Aggiungi Prodotto
                        </button>
                    </div>

                    <!-- Tabella Prodotti -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="itemsTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prodotto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantità (kg)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N. Colli</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prezzo €/kg</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Totale €</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="itemsBody">
                                @forelse($transportDocument->items as $item)
                                    <tr data-item-id="{{ $item->id }}">
                                        <td class="px-6 py-4">{{ $item->product->nome_completo ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ number_format($item->quantita_kg, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4">{{ $item->numero_colli }}</td>
                                        <td class="px-6 py-4">{{ number_format($item->prezzo_unitario, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 font-semibold">{{ number_format($item->totale, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            <button type="button" class="text-red-600 hover:underline delete-item"
                                                    data-item-id="{{ $item->id }}">
                                                Elimina
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="emptyRow">
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Nessun prodotto aggiunto. Clicca su "+ Aggiungi Prodotto" per iniziare.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right font-semibold">Totale Imponibile:</td>
                                    <td class="px-6 py-3 font-bold text-lg" id="totaleImponibile">
                                        € {{ number_format($transportDocument->totale_imponibile ?? 0, 2, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right font-semibold">IVA (10%):</td>
                                    <td class="px-6 py-3 font-semibold" id="totaleIva">
                                        € {{ number_format(($transportDocument->totale_imponibile ?? 0) * 0.10, 2, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-6 py-3 text-right font-semibold">Totale Documento:</td>
                                    <td class="px-6 py-3 font-bold text-xl text-blue-600" id="totaleDocumento">
                                        € {{ number_format(($transportDocument->totale_imponibile ?? 0) * 1.10, 2, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Aggiungi Prodotto -->
<div id="addItemModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Aggiungi Prodotto</h3>
            <form id="addItemForm">
                @csrf
                <input type="hidden" name="transport_document_id" value="{{ $transportDocument->id }}">

                <div class="mb-4">
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Prodotto *</label>
                    <select name="product_id" id="product_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">-- Seleziona --</option>
                        @foreach(\App\Models\Product::active()->orderBy('nome_commerciale')->get() as $product)
                            <option value="{{ $product->id }}" data-prezzo="{{ $product->prezzo_base }}">
                                {{ $product->nome_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="quantita_kg" class="block text-sm font-medium text-gray-700">Quantità (kg) *</label>
                    <input type="number" name="quantita_kg" id="quantita_kg" step="0.01" min="0" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="numero_colli" class="block text-sm font-medium text-gray-700">N. Colli *</label>
                    <input type="number" name="numero_colli" id="numero_colli" min="0" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="prezzo_unitario" class="block text-sm font-medium text-gray-700">Prezzo €/kg *</label>
                    <input type="number" name="prezzo_unitario" id="prezzo_unitario" step="0.01" min="0" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" id="closeModalBtn"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Annulla
                    </button>
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Aggiungi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('addItemModal');
    const addItemBtn = document.getElementById('addItemBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const addItemForm = document.getElementById('addItemForm');
    const productSelect = document.getElementById('product_id');
    const prezzoInput = document.getElementById('prezzo_unitario');

    // Apri modal
    addItemBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    // Chiudi modal
    closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        addItemForm.reset();
    });

    // Chiudi modal cliccando fuori
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            addItemForm.reset();
        }
    });

    // Auto-compila prezzo quando si seleziona prodotto
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const prezzo = selectedOption.dataset.prezzo;
        if (prezzo) {
            prezzoInput.value = prezzo;
        }
    });

    // Aggiungi prodotto (AJAX)
    addItemForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('{{ route("transport-document-items.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Ricarica pagina per aggiornare tabella e totali
                location.reload();
            } else {
                alert('Errore: ' + (data.message || 'Impossibile aggiungere il prodotto'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Errore durante l\'aggiunta del prodotto');
        });
    });

    // Elimina prodotto (AJAX)
    document.querySelectorAll('.delete-item').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('Sei sicuro di voler eliminare questo prodotto?')) {
                return;
            }

            const itemId = this.dataset.itemId;

            fetch(`/transport-document-items/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Errore: ' + (data.message || 'Impossibile eliminare il prodotto'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Errore durante l\'eliminazione del prodotto');
            });
        });
    });
});
</script>
@endsection
