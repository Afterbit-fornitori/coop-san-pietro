@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Nuova Zona di Produzione</h2>
            <a href="{{ route('production-zones.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
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

        <form action="{{ route('production-zones.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Codice Ministeriale -->
                <div>
                    <label for="codice" class="block text-sm font-medium mb-2">Codice Ministeriale *</label>
                    <input type="text" name="codice" id="codice" value="{{ old('codice') }}" required
                        placeholder="es. 006FE156 - LI-FE6 - 81M/182807/2016"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                    <p class="text-xs text-gray-500 mt-1">Codice identificativo ministeriale della zona</p>
                </div>

                <!-- Nome -->
                <div>
                    <label for="nome" class="block text-sm font-medium mb-2">Nome Zona *</label>
                    <input type="text" name="nome" id="nome" value="{{ old('nome') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                </div>

                <!-- Metri Quadrati -->
                <div>
                    <label for="mq" class="block text-sm font-medium mb-2">Superficie (m²)</label>
                    <input type="number" step="0.01" name="mq" id="mq" value="{{ old('mq') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                </div>

                <!-- Classe Sanitaria -->
                <div>
                    <label for="classe_sanitaria" class="block text-sm font-medium mb-2">Classe Sanitaria</label>
                    <select name="classe_sanitaria" id="classe_sanitaria"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                        <option value="">Seleziona Classe</option>
                        <option value="A" {{ old('classe_sanitaria') === 'A' ? 'selected' : '' }}>Classe A</option>
                        <option value="B" {{ old('classe_sanitaria') === 'B' ? 'selected' : '' }}>Classe B</option>
                        <option value="C" {{ old('classe_sanitaria') === 'C' ? 'selected' : '' }}>Classe C</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">A = consumo diretto, B = depurazione, C = reimmersione</p>
                </div>

                <!-- Declassificazione Temporanea -->
                <div>
                    <div class="flex items-center mb-2">
                        <input type="checkbox" name="declassificazione_temporanea" id="declassificazione_temporanea" value="1"
                            {{ old('declassificazione_temporanea') ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="declassificazione_temporanea" class="ml-2 block text-sm font-medium">
                            Declassificazione Temporanea
                        </label>
                    </div>
                    <p class="text-xs text-gray-500">Indica se la zona è temporaneamente declassificata</p>
                </div>

                <!-- Data Declassificazione -->
                <div>
                    <label for="data_declassificazione" class="block text-sm font-medium mb-2">Data Declassificazione</label>
                    <input type="date" name="data_declassificazione" id="data_declassificazione" value="{{ old('data_declassificazione') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                    <p class="text-xs text-gray-500 mt-1">Data di inizio della declassificazione</p>
                </div>

                <!-- Stato Attivo -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm font-medium">
                            Zona Attiva
                        </label>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-300 dark:border-gray-600">
                <a href="{{ route('production-zones.index') }}" class="px-6 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Annulla
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Crea Zona
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
