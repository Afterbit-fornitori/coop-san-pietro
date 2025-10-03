@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Nuovo Prodotto</h2>
            <a href="{{ route('products.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
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

        <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Codice Prodotto -->
                <div>
                    <label for="codice" class="block text-sm font-medium mb-2">Codice Prodotto *</label>
                    <input type="text" name="codice" id="codice" value="{{ old('codice') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                </div>

                <!-- Nome Commerciale -->
                <div>
                    <label for="nome_commerciale" class="block text-sm font-medium mb-2">Nome Commerciale *</label>
                    <input type="text" name="nome_commerciale" id="nome_commerciale" value="{{ old('nome_commerciale') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                </div>

                <!-- Nome Scientifico -->
                <div class="md:col-span-2">
                    <label for="nome_scientifico" class="block text-sm font-medium mb-2">Nome Scientifico *</label>
                    <input type="text" name="nome_scientifico" id="nome_scientifico" value="{{ old('nome_scientifico') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                </div>

                <!-- Specie -->
                <div>
                    <label for="specie" class="block text-sm font-medium mb-2">Specie *</label>
                    <select name="specie" id="specie" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                        <option value="">Seleziona Specie</option>
                        <option value="VONGOLE" {{ old('specie') === 'VONGOLE' ? 'selected' : '' }}>VONGOLE</option>
                        <option value="COZZE" {{ old('specie') === 'COZZE' ? 'selected' : '' }}>COZZE</option>
                        <option value="OSTRICHE" {{ old('specie') === 'OSTRICHE' ? 'selected' : '' }}>OSTRICHE</option>
                        <option value="ALTRO" {{ old('specie') === 'ALTRO' ? 'selected' : '' }}>ALTRO</option>
                    </select>
                </div>

                <!-- Pezzatura -->
                <div>
                    <label for="pezzatura" class="block text-sm font-medium mb-2">Pezzatura *</label>
                    <select name="pezzatura" id="pezzatura" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                        <option value="">Seleziona Pezzatura</option>
                        <option value="MICRO" {{ old('pezzatura') === 'MICRO' ? 'selected' : '' }}>MICRO</option>
                        <option value="PICCOLA" {{ old('pezzatura') === 'PICCOLA' ? 'selected' : '' }}>PICCOLA</option>
                        <option value="MEDIA" {{ old('pezzatura') === 'MEDIA' ? 'selected' : '' }}>MEDIA</option>
                        <option value="GROSSA" {{ old('pezzatura') === 'GROSSA' ? 'selected' : '' }}>GROSSA</option>
                        <option value="SUPER" {{ old('pezzatura') === 'SUPER' ? 'selected' : '' }}>SUPER</option>
                        <option value="SGRANATA" {{ old('pezzatura') === 'SGRANATA' ? 'selected' : '' }}>SGRANATA</option>
                        <option value="TRECCIA" {{ old('pezzatura') === 'TRECCIA' ? 'selected' : '' }}>TRECCIA</option>
                    </select>
                </div>

                <!-- Destinazione -->
                <div>
                    <label for="destinazione" class="block text-sm font-medium mb-2">Destinazione *</label>
                    <select name="destinazione" id="destinazione" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                        <option value="">Seleziona Destinazione</option>
                        <option value="CONSUMO" {{ old('destinazione') === 'CONSUMO' ? 'selected' : '' }}>CONSUMO</option>
                        <option value="REIMMERSIONE" {{ old('destinazione') === 'REIMMERSIONE' ? 'selected' : '' }}>REIMMERSIONE</option>
                        <option value="DEPURAZIONE" {{ old('destinazione') === 'DEPURAZIONE' ? 'selected' : '' }}>DEPURAZIONE</option>
                    </select>
                </div>

                <!-- Prezzo Base -->
                <div>
                    <label for="prezzo_base" class="block text-sm font-medium mb-2">Prezzo Base (€) *</label>
                    <input type="number" step="0.01" name="prezzo_base" id="prezzo_base" value="{{ old('prezzo_base') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                </div>

                <!-- Unità di Misura -->
                <div>
                    <label for="unita_misura" class="block text-sm font-medium mb-2">Unità di Misura *</label>
                    <select name="unita_misura" id="unita_misura" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                        <option value="">Seleziona Unità</option>
                        <option value="kg" {{ old('unita_misura') === 'kg' ? 'selected' : '' }}>kg</option>
                        <option value="pz" {{ old('unita_misura') === 'pz' ? 'selected' : '' }}>pz (pezzi)</option>
                        <option value="confezione" {{ old('unita_misura') === 'confezione' ? 'selected' : '' }}>confezione</option>
                    </select>
                </div>

                <!-- Stato Attivo -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm font-medium">
                            Prodotto Attivo
                        </label>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-300 dark:border-gray-600">
                <a href="{{ route('products.index') }}" class="px-6 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Annulla
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Crea Prodotto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
