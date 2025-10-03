@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex items-center mb-6">
            <a href="{{ route('weekly-records.index') }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                ← Torna alla lista
            </a>
            <h2 class="text-2xl font-semibold">Modifica Registro Settimanale - Settimana {{ $weeklyRecord->week }}/{{ $weeklyRecord->year }}</h2>
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

        <form action="{{ route('weekly-records.update', $weeklyRecord) }}" method="POST" class="space-y-6" id="weeklyRecordForm">
            @csrf
            @method('PUT')

            <!-- Informazioni Base -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-medium mb-4">Informazioni Generali</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="member_id" class="block text-sm font-medium">Socio *</label>
                        <select id="member_id" name="member_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600"
                                required>
                            <option value="">Seleziona Socio</option>
                            @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id', $weeklyRecord->member_id) == $member->id ? 'selected' : '' }}>
                                {{ $member->full_name }} - {{ $member->rpm_registration }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="year" class="block text-sm font-medium">Anno *</label>
                        <input type="number" id="year" name="year" value="{{ old('year', $weeklyRecord->year) }}" min="2020" max="2030"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600"
                               required>
                    </div>
                    <div>
                        <label for="week" class="block text-sm font-medium">Settimana *</label>
                        <input type="number" id="week" name="week" value="{{ old('week', $weeklyRecord->week) }}" min="1" max="53"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600"
                               required>
                    </div>
                    <div>
                        <label for="start_date" class="block text-sm font-medium">Data Inizio *</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $weeklyRecord->start_date?->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600"
                               required>
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium">Data Fine *</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $weeklyRecord->end_date?->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600"
                               required>
                    </div>
                    <div>
                        <label for="invoice_number" class="block text-sm font-medium">Numero Fattura</label>
                        <input type="text" id="invoice_number" name="invoice_number" value="{{ old('invoice_number', $weeklyRecord->invoice_number) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                </div>
            </div>

            <!-- Reimmersione Interna -->
            <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                <h3 class="text-lg font-medium mb-4">Reimmersione Interna</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="kg_micro_internal_reimmersion" class="block text-sm font-medium">Kg Micro</label>
                        <input type="number" step="0.01" id="kg_micro_internal_reimmersion" name="kg_micro_internal_reimmersion" value="{{ old('kg_micro_internal_reimmersion', $weeklyRecord->kg_micro_internal_reimmersion) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="price_micro_internal_reimmersion" class="block text-sm font-medium">Prezzo Micro (€/kg)</label>
                        <input type="number" step="0.01" id="price_micro_internal_reimmersion" name="price_micro_internal_reimmersion" value="{{ old('price_micro_internal_reimmersion', $weeklyRecord->price_micro_internal_reimmersion) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="kg_small_internal_reimmersion" class="block text-sm font-medium">Kg Piccola</label>
                        <input type="number" step="0.01" id="kg_small_internal_reimmersion" name="kg_small_internal_reimmersion" value="{{ old('kg_small_internal_reimmersion', $weeklyRecord->kg_small_internal_reimmersion) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="price_small_internal_reimmersion" class="block text-sm font-medium">Prezzo Piccola (€/kg)</label>
                        <input type="number" step="0.01" id="price_small_internal_reimmersion" name="price_small_internal_reimmersion" value="{{ old('price_small_internal_reimmersion', $weeklyRecord->price_small_internal_reimmersion) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                </div>
            </div>

            <!-- Reimmersione Rivendita -->
            <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                <h3 class="text-lg font-medium mb-4">Reimmersione Rivendita</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="kg_micro_resale_reimmersion" class="block text-sm font-medium">Kg Micro</label>
                        <input type="number" step="0.01" id="kg_micro_resale_reimmersion" name="kg_micro_resale_reimmersion" value="{{ old('kg_micro_resale_reimmersion', $weeklyRecord->kg_micro_resale_reimmersion) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="price_micro_resale_reimmersion" class="block text-sm font-medium">Prezzo Micro (€/kg)</label>
                        <input type="number" step="0.01" id="price_micro_resale_reimmersion" name="price_micro_resale_reimmersion" value="{{ old('price_micro_resale_reimmersion', $weeklyRecord->price_micro_resale_reimmersion) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="kg_small_resale_reimmersion" class="block text-sm font-medium">Kg Piccola</label>
                        <input type="number" step="0.01" id="kg_small_resale_reimmersion" name="kg_small_resale_reimmersion" value="{{ old('kg_small_resale_reimmersion', $weeklyRecord->kg_small_resale_reimmersion) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="price_small_resale_reimmersion" class="block text-sm font-medium">Prezzo Piccola (€/kg)</label>
                        <input type="number" step="0.01" id="price_small_resale_reimmersion" name="price_small_resale_reimmersion" value="{{ old('price_small_resale_reimmersion', $weeklyRecord->price_small_resale_reimmersion) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                </div>
            </div>

            <!-- Da Consumo -->
            <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                <h3 class="text-lg font-medium mb-4">Da Consumo</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="kg_medium_consumption" class="block text-sm font-medium">Kg Media</label>
                        <input type="number" step="0.01" id="kg_medium_consumption" name="kg_medium_consumption" value="{{ old('kg_medium_consumption', $weeklyRecord->kg_medium_consumption) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="price_medium_consumption" class="block text-sm font-medium">Prezzo Media (€/kg)</label>
                        <input type="number" step="0.01" id="price_medium_consumption" name="price_medium_consumption" value="{{ old('price_medium_consumption', $weeklyRecord->price_medium_consumption) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="kg_large_consumption" class="block text-sm font-medium">Kg Grossa</label>
                        <input type="number" step="0.01" id="kg_large_consumption" name="kg_large_consumption" value="{{ old('kg_large_consumption', $weeklyRecord->kg_large_consumption) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="price_large_consumption" class="block text-sm font-medium">Prezzo Grossa (€/kg)</label>
                        <input type="number" step="0.01" id="price_large_consumption" name="price_large_consumption" value="{{ old('price_large_consumption', $weeklyRecord->price_large_consumption) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="kg_super_consumption" class="block text-sm font-medium">Kg Super</label>
                        <input type="number" step="0.01" id="kg_super_consumption" name="kg_super_consumption" value="{{ old('kg_super_consumption', $weeklyRecord->kg_super_consumption) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="price_super_consumption" class="block text-sm font-medium">Prezzo Super (€/kg)</label>
                        <input type="number" step="0.01" id="price_super_consumption" name="price_super_consumption" value="{{ old('price_super_consumption', $weeklyRecord->price_super_consumption) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                </div>
            </div>

            <!-- Calcoli Finali -->
            <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg">
                <h3 class="text-lg font-medium mb-4">Calcoli Finali</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label for="taxable_amount" class="block text-sm font-medium">Imponibile (€)</label>
                        <input type="number" step="0.01" id="taxable_amount" name="taxable_amount" value="{{ old('taxable_amount', $weeklyRecord->taxable_amount) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="advance_paid" class="block text-sm font-medium">Acconto Pagato (€)</label>
                        <input type="number" step="0.01" id="advance_paid" name="advance_paid" value="{{ old('advance_paid', $weeklyRecord->advance_paid) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="withholding_tax" class="block text-sm font-medium">Ritenuta d'Acconto (€)</label>
                        <input type="number" step="0.01" id="withholding_tax" name="withholding_tax" value="{{ old('withholding_tax', $weeklyRecord->withholding_tax) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="profis" class="block text-sm font-medium">PROFIS (€)</label>
                        <input type="number" step="0.01" id="profis" name="profis" value="{{ old('profis', $weeklyRecord->profis) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                    <div>
                        <label for="bank_transfer" class="block text-sm font-medium">Bonifico (€)</label>
                        <input type="number" step="0.01" id="bank_transfer" name="bank_transfer" value="{{ old('bank_transfer', $weeklyRecord->bank_transfer) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('weekly-records.index') }}" class="px-6 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Annulla
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Aggiorna Registro
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
