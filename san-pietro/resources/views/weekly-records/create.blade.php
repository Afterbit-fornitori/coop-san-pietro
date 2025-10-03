@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex items-center mb-6">
            <a href="{{ route('weekly-records.index') }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                ← Torna alla lista
            </a>
            <h2 class="text-2xl font-semibold">Nuovo Registro Settimanale</h2>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-300 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('weekly-records.store') }}" method="POST" class="space-y-6" id="weeklyRecordForm">
            @csrf

            <!-- Informazioni Base -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-medium mb-4">Informazioni Generali</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="member_id" class="block text-sm font-medium">Socio *</label>
                        <select id="member_id" name="member_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                            <option value="">Seleziona Socio</option>
                            @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->full_name }} - {{ $member->rpm_registration }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="year" class="block text-sm font-medium">Anno *</label>
                        <input type="number" id="year" name="year" value="{{ old('year', date('Y')) }}" min="2020" max="2030"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>
                    <div>
                        <label for="week" class="block text-sm font-medium">Settimana *</label>
                        <input type="number" id="week" name="week" value="{{ old('week', date('W')) }}" min="1" max="53"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium">Data Inizio *</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium">Data Fine *</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>
                    <div>
                        <label for="invoice_number" class="block text-sm font-medium">Numero Fattura</label>
                        <input type="text" id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Reimmersione Interna -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-medium mb-4">Reimmersione Interna</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Micro -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-blue-600">Micro</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="kg_micro_reimmersione_interna" class="block text-sm font-medium">Kg</label>
                                <input type="number" step="0.01" id="kg_micro_reimmersione_interna"
                                       name="kg_micro_reimmersione_interna" value="{{ old('kg_micro_reimmersione_interna', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input">
                            </div>
                            <div>
                                <label for="prezzo_micro_reimmersione_interna" class="block text-sm font-medium">Prezzo €/kg</label>
                                <input type="number" step="0.01" id="prezzo_micro_reimmersione_interna"
                                       name="prezzo_micro_reimmersione_interna" value="{{ old('prezzo_micro_reimmersione_interna', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 price-input">
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            Subtotale: €<span id="subtotal_micro_reimmersione">0.00</span>
                        </div>
                    </div>

                    <!-- Piccola -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-blue-600">Piccola</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="kg_piccola_reimmersione_interna" class="block text-sm font-medium">Kg</label>
                                <input type="number" step="0.01" id="kg_piccola_reimmersione_interna"
                                       name="kg_piccola_reimmersione_interna" value="{{ old('kg_piccola_reimmersione_interna', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input">
                            </div>
                            <div>
                                <label for="prezzo_piccola_reimmersione_interna" class="block text-sm font-medium">Prezzo €/kg</label>
                                <input type="number" step="0.01" id="prezzo_piccola_reimmersione_interna"
                                       name="prezzo_piccola_reimmersione_interna" value="{{ old('prezzo_piccola_reimmersione_interna', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 price-input">
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            Subtotale: €<span id="subtotal_piccola_reimmersione">0.00</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900 rounded">
                    <div class="text-sm font-medium">
                        Totale Reimmersione: <span id="kg_totale_reimmersione">0.00</span> kg - €<span id="totale_reimmersione">0.00</span>
                    </div>
                </div>
            </div>

            <!-- Consumo Diretto -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-medium mb-4">Consumo Diretto</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Micro -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-green-600">Micro</h4>
                        <div class="space-y-2">
                            <div>
                                <label for="kg_micro_consumo_diretto" class="block text-sm font-medium">Kg</label>
                                <input type="number" step="0.01" id="kg_micro_consumo_diretto"
                                       name="kg_micro_consumo_diretto" value="{{ old('kg_micro_consumo_diretto', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input">
                            </div>
                            <div>
                                <label for="prezzo_micro_consumo_diretto" class="block text-sm font-medium">Prezzo €/kg</label>
                                <input type="number" step="0.01" id="prezzo_micro_consumo_diretto"
                                       name="prezzo_micro_consumo_diretto" value="{{ old('prezzo_micro_consumo_diretto', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 price-input">
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            Subtotale: €<span id="subtotal_micro_consumo">0.00</span>
                        </div>
                    </div>

                    <!-- Piccola -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-green-600">Piccola</h4>
                        <div class="space-y-2">
                            <div>
                                <label for="kg_piccola_consumo_diretto" class="block text-sm font-medium">Kg</label>
                                <input type="number" step="0.01" id="kg_piccola_consumo_diretto"
                                       name="kg_piccola_consumo_diretto" value="{{ old('kg_piccola_consumo_diretto', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input">
                            </div>
                            <div>
                                <label for="prezzo_piccola_consumo_diretto" class="block text-sm font-medium">Prezzo €/kg</label>
                                <input type="number" step="0.01" id="prezzo_piccola_consumo_diretto"
                                       name="prezzo_piccola_consumo_diretto" value="{{ old('prezzo_piccola_consumo_diretto', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 price-input">
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            Subtotale: €<span id="subtotal_piccola_consumo">0.00</span>
                        </div>
                    </div>

                    <!-- Media -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-green-600">Media</h4>
                        <div class="space-y-2">
                            <div>
                                <label for="kg_media_consumo_diretto" class="block text-sm font-medium">Kg</label>
                                <input type="number" step="0.01" id="kg_media_consumo_diretto"
                                       name="kg_media_consumo_diretto" value="{{ old('kg_media_consumo_diretto', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input">
                            </div>
                            <div>
                                <label for="prezzo_media_consumo_diretto" class="block text-sm font-medium">Prezzo €/kg</label>
                                <input type="number" step="0.01" id="prezzo_media_consumo_diretto"
                                       name="prezzo_media_consumo_diretto" value="{{ old('prezzo_media_consumo_diretto', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 price-input">
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            Subtotale: €<span id="subtotal_media_consumo">0.00</span>
                        </div>
                    </div>

                    <!-- Grande -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-green-600">Grande</h4>
                        <div class="space-y-2">
                            <div>
                                <label for="kg_grande_consumo_diretto" class="block text-sm font-medium">Kg</label>
                                <input type="number" step="0.01" id="kg_grande_consumo_diretto"
                                       name="kg_grande_consumo_diretto" value="{{ old('kg_grande_consumo_diretto', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input">
                            </div>
                            <div>
                                <label for="prezzo_grande_consumo_diretto" class="block text-sm font-medium">Prezzo €/kg</label>
                                <input type="number" step="0.01" id="prezzo_grande_consumo_diretto"
                                       name="prezzo_grande_consumo_diretto" value="{{ old('prezzo_grande_consumo_diretto', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 price-input">
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            Subtotale: €<span id="subtotal_grande_consumo">0.00</span>
                        </div>
                    </div>

                    <!-- Super -->
                    <div class="space-y-4">
                        <h4 class="font-medium text-green-600">Super</h4>
                        <div class="space-y-2">
                            <div>
                                <label for="kg_super_consumo_diretto" class="block text-sm font-medium">Kg</label>
                                <input type="number" step="0.01" id="kg_super_consumo_diretto"
                                       name="kg_super_consumo_diretto" value="{{ old('kg_super_consumo_diretto', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 quantity-input">
                            </div>
                            <div>
                                <label for="prezzo_super_consumo_diretto" class="block text-sm font-medium">Prezzo €/kg</label>
                                <input type="number" step="0.01" id="prezzo_super_consumo_diretto"
                                       name="prezzo_super_consumo_diretto" value="{{ old('prezzo_super_consumo_diretto', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 price-input">
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            Subtotale: €<span id="subtotal_super_consumo">0.00</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-green-50 dark:bg-green-900 rounded">
                    <div class="text-sm font-medium">
                        Totale Consumo Diretto: <span id="kg_totale_consumo">0.00</span> kg - €<span id="totale_consumo">0.00</span>
                    </div>
                </div>
            </div>

            <!-- Totali Generali -->
            <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                <div class="text-lg font-bold text-center">
                    TOTALE GENERALE: <span id="kg_totali_generali">0.00</span> kg - €<span id="importo_totale_generale">0.00</span>
                </div>
            </div>

            <!-- Pulsanti -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('weekly-records.index') }}"
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Annulla
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Crea Registro
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Funzione per calcolare i subtotali e totali
    function calculateTotals() {
        // Reimmersione Interna
        const microReimKg = parseFloat(document.getElementById('kg_micro_reimmersione_interna').value) || 0;
        const microReimPrice = parseFloat(document.getElementById('prezzo_micro_reimmersione_interna').value) || 0;
        const piccolaReimKg = parseFloat(document.getElementById('kg_piccola_reimmersione_interna').value) || 0;
        const piccolaReimPrice = parseFloat(document.getElementById('prezzo_piccola_reimmersione_interna').value) || 0;

        const subtotalMicroReim = microReimKg * microReimPrice;
        const subtotalPiccolaReim = piccolaReimKg * piccolaReimPrice;

        document.getElementById('subtotal_micro_reimmersione').textContent = subtotalMicroReim.toFixed(2);
        document.getElementById('subtotal_piccola_reimmersione').textContent = subtotalPiccolaReim.toFixed(2);

        const kgTotaleReimmersione = microReimKg + piccolaReimKg;
        const totaleReimmersione = subtotalMicroReim + subtotalPiccolaReim;

        document.getElementById('kg_totale_reimmersione').textContent = kgTotaleReimmersione.toFixed(2);
        document.getElementById('totale_reimmersione').textContent = totaleReimmersione.toFixed(2);

        // Consumo Diretto
        const categories = ['micro', 'piccola', 'media', 'grande', 'super'];
        let kgTotaleConsumo = 0;
        let totaleConsumo = 0;

        categories.forEach(category => {
            const kg = parseFloat(document.getElementById(`kg_${category}_consumo_diretto`).value) || 0;
            const price = parseFloat(document.getElementById(`prezzo_${category}_consumo_diretto`).value) || 0;
            const subtotal = kg * price;

            document.getElementById(`subtotal_${category}_consumo`).textContent = subtotal.toFixed(2);

            kgTotaleConsumo += kg;
            totaleConsumo += subtotal;
        });

        document.getElementById('kg_totale_consumo').textContent = kgTotaleConsumo.toFixed(2);
        document.getElementById('totale_consumo').textContent = totaleConsumo.toFixed(2);

        // Totali Generali
        const kgTotaliGenerali = kgTotaleReimmersione + kgTotaleConsumo;
        const importoTotaleGenerale = totaleReimmersione + totaleConsumo;

        document.getElementById('kg_totali_generali').textContent = kgTotaliGenerali.toFixed(2);
        document.getElementById('importo_totale_generale').textContent = importoTotaleGenerale.toFixed(2);
    }

    // Aggiungi event listeners a tutti gli input
    document.querySelectorAll('.quantity-input, .price-input').forEach(input => {
        input.addEventListener('input', calculateTotals);
    });

    // Calcola i totali iniziali
    calculateTotals();
});
</script>
@endsection