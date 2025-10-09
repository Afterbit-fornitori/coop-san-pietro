@extends('layouts.app')

@section('content')
<style>
    .btn-annulla:hover {
        background-color: #4B5563;
        color: white;

    }
</style>
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Nuovo Cliente</h2>
            <a href="{{ route('clients.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                Torna alla Lista
            </a>
        </div>

        @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-300 rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('clients.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Dati Anagrafici -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Dati Anagrafici</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="business_name" class="block text-sm font-medium mb-2">Ragione Sociale *</label>
                        <input type="text" name="business_name" id="business_name" value="{{ old('business_name') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                    </div>

                    <div>
                        <label for="vat_number" class="block text-sm font-medium mb-2">Partita IVA</label>
                        <input type="text" name="vat_number" id="vat_number" value="{{ old('vat_number') }}" maxlength="11"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500"
                            placeholder="12345678901">
                    </div>

                    <div>
                        <label for="tax_code" class="block text-sm font-medium mb-2">Codice Fiscale</label>
                        <input type="text" name="tax_code" id="tax_code" value="{{ old('tax_code') }}" maxlength="16"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500"
                            placeholder="RSSMRA80A01H501X">
                    </div>
                </div>
            </div>

            <!-- Indirizzo -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Indirizzo</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium mb-2">Via/Piazza *</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium mb-2">Città *</label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="postal_code" class="block text-sm font-medium mb-2">CAP *</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" maxlength="10" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500"
                                placeholder="44022">
                        </div>

                        <div>
                            <label for="province" class="block text-sm font-medium mb-2">Provincia *</label>
                            <input type="text" name="province" id="province" value="{{ old('province') }}" maxlength="2" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500"
                                placeholder="FE">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contatti -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Contatti</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-sm font-medium mb-2">Telefono</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500"
                            placeholder="+39 0533 123456">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500"
                            placeholder="info@cliente.it">
                    </div>

                    <div>
                        <label for="pec" class="block text-sm font-medium mb-2">PEC</label>
                        <input type="email" name="pec" id="pec" value="{{ old('pec') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500"
                            placeholder="cliente@pec.it">
                    </div>

                    <div>
                        <label for="sdi_code" class="block text-sm font-medium mb-2">Codice SDI (Fatturazione Elettronica)</label>
                        <input type="text" name="sdi_code" id="sdi_code" value="{{ old('sdi_code') }}" maxlength="7"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500"
                            placeholder="XXXXXXX">
                    </div>
                </div>
            </div>

            <!-- Note e Stato -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Altre Informazioni</h3>

                <div class="space-y-4">
                    <div>
                        <label for="note" class="block text-sm font-medium mb-2">Note</label>
                        <textarea name="note" id="note" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">{{ old('note') }}</textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm font-medium">
                            Cliente Attivo
                        </label>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('clients.index') }}" class="btn-annulla px-6 py-2 bg-white text-black border border-black rounded border-black">
                    Annulla
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Crea Cliente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection