@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex items-center mb-6">
                    <a href="{{ route('weekly-records.index') }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                        ← Torna alla lista
                    </a>
                    <h2 class="text-2xl font-semibold">Nuovo Registro Produzione Settimanale</h2>
                </div>

                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                    <p class="font-semibold mb-2">Errori di validazione:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($members->isEmpty())
                <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700">
                    <p><strong>Attenzione:</strong> Non ci sono soci disponibili. Crea prima almeno un socio.</p>
                    <a href="{{ route('members.create') }}" class="text-blue-600 hover:underline">Crea Socio</a>
                </div>
                @endif

                <form action="{{ route('weekly-records.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Informazioni Base -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 border-b pb-2">Informazioni Generali</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="member_id" class="block text-sm font-medium text-gray-700">Socio *</label>
                                <select id="member_id" name="member_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        required {{ $members->isEmpty() ? 'disabled' : '' }}>
                                    <option value="">Seleziona Socio</option>
                                    @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->full_name }} @if($member->rpm_registration) - {{ $member->rpm_registration }} @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('member_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700">Anno *</label>
                                <input type="number" id="year" name="year" value="{{ old('year', date('Y')) }}" min="2020" max="2030"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                                @error('year')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="week" class="block text-sm font-medium text-gray-700">Settimana *</label>
                                <input type="number" id="week" name="week" value="{{ old('week', date('W')) }}" min="1" max="53"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                                @error('week')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Data Inizio *</label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                                @error('start_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Data Fine *</label>
                                <input type="date" id="end_date" name="end_date" value="{{ old('end_date', date('Y-m-d')) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                                @error('end_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="invoice_number" class="block text-sm font-medium text-gray-700">Numero Fattura</label>
                                <input type="text" id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('invoice_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Reimmersione Interna -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 border-b border-blue-200 pb-2">Reimmersione Interna</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Micro -->
                            <div class="bg-white p-4 rounded border border-blue-200">
                                <h4 class="font-medium text-blue-700 mb-3">Micro</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kg</label>
                                        <input type="number" step="0.01" name="kg_micro_internal_reimmersion"
                                               value="{{ old('kg_micro_internal_reimmersion', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prezzo €/kg</label>
                                        <input type="number" step="0.01" name="price_micro_internal_reimmersion"
                                               value="{{ old('price_micro_internal_reimmersion', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Piccola -->
                            <div class="bg-white p-4 rounded border border-blue-200">
                                <h4 class="font-medium text-blue-700 mb-3">Piccola</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kg</label>
                                        <input type="number" step="0.01" name="kg_small_internal_reimmersion"
                                               value="{{ old('kg_small_internal_reimmersion', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prezzo €/kg</label>
                                        <input type="number" step="0.01" name="price_small_internal_reimmersion"
                                               value="{{ old('price_small_internal_reimmersion', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reimmersione Rivendita -->
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 border-b border-purple-200 pb-2">Reimmersione Rivendita</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Micro -->
                            <div class="bg-white p-4 rounded border border-purple-200">
                                <h4 class="font-medium text-purple-700 mb-3">Micro</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kg</label>
                                        <input type="number" step="0.01" name="kg_micro_resale_reimmersion"
                                               value="{{ old('kg_micro_resale_reimmersion', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prezzo €/kg</label>
                                        <input type="number" step="0.01" name="price_micro_resale_reimmersion"
                                               value="{{ old('price_micro_resale_reimmersion', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Piccola -->
                            <div class="bg-white p-4 rounded border border-purple-200">
                                <h4 class="font-medium text-purple-700 mb-3">Piccola</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kg</label>
                                        <input type="number" step="0.01" name="kg_small_resale_reimmersion"
                                               value="{{ old('kg_small_resale_reimmersion', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prezzo €/kg</label>
                                        <input type="number" step="0.01" name="price_small_resale_reimmersion"
                                               value="{{ old('price_small_resale_reimmersion', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Da Consumo -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 border-b border-green-200 pb-2">Da Consumo</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Media -->
                            <div class="bg-white p-4 rounded border border-green-200">
                                <h4 class="font-medium text-green-700 mb-3">Media</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kg</label>
                                        <input type="number" step="0.01" name="kg_medium_consumption"
                                               value="{{ old('kg_medium_consumption', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prezzo €/kg</label>
                                        <input type="number" step="0.01" name="price_medium_consumption"
                                               value="{{ old('price_medium_consumption', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Grossa -->
                            <div class="bg-white p-4 rounded border border-green-200">
                                <h4 class="font-medium text-green-700 mb-3">Grossa</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kg</label>
                                        <input type="number" step="0.01" name="kg_large_consumption"
                                               value="{{ old('kg_large_consumption', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prezzo €/kg</label>
                                        <input type="number" step="0.01" name="price_large_consumption"
                                               value="{{ old('price_large_consumption', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Super -->
                            <div class="bg-white p-4 rounded border border-green-200">
                                <h4 class="font-medium text-green-700 mb-3">Super</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kg</label>
                                        <input type="number" step="0.01" name="kg_super_consumption"
                                               value="{{ old('kg_super_consumption', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Prezzo €/kg</label>
                                        <input type="number" step="0.01" name="price_super_consumption"
                                               value="{{ old('price_super_consumption', 0) }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Calcoli (opzionali - calcolati automaticamente dal backend) -->
                    <div class="bg-yellow-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 border-b border-yellow-200 pb-2">Calcoli Bonifico (Opzionali)</h3>
                        <p class="text-sm text-gray-600 mb-4">Questi campi possono essere lasciati a 0 se non disponibili. Verranno calcolati automaticamente se necessario.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Imponibile €</label>
                                <input type="number" step="0.01" name="taxable_amount" value="{{ old('taxable_amount', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Acconto Pagato €</label>
                                <input type="number" step="0.01" name="advance_paid" value="{{ old('advance_paid', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ritenuta d'Acconto €</label>
                                <input type="number" step="0.01" name="withholding_tax" value="{{ old('withholding_tax', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">PROFIS €</label>
                                <input type="number" step="0.01" name="profis" value="{{ old('profis', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Bonifico €</label>
                                <input type="number" step="0.01" name="bank_transfer" value="{{ old('bank_transfer', 0) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Pulsanti -->
                    <div class="flex justify-end space-x-3 pt-6 border-t">
                        <a href="{{ route('weekly-records.index') }}"
                           class="px-6 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Annulla
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                                {{ $members->isEmpty() ? 'disabled' : '' }}>
                            Crea Registro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
