@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Nuova Registrazione Carico/Scarico</h2>
            <a href="{{ route('loading-unloading.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
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

        <form action="{{ route('loading-unloading.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data Operazione -->
                <div>
                    <label for="data_operazione" class="block text-sm font-medium mb-2">Data Operazione *</label>
                    <input type="date" name="data_operazione" id="data_operazione" value="{{ old('data_operazione', date('Y-m-d')) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                </div>

                <!-- Tipo Operazione -->
                <div>
                    <label for="tipo_operazione" class="block text-sm font-medium mb-2">Tipo Operazione *</label>
                    <select name="tipo_operazione" id="tipo_operazione" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                        <option value="">Seleziona Tipo</option>
                        <option value="CARICO" {{ old('tipo_operazione') === 'CARICO' ? 'selected' : '' }}>CARICO</option>
                        <option value="SCARICO" {{ old('tipo_operazione') === 'SCARICO' ? 'selected' : '' }}>SCARICO</option>
                    </select>
                </div>

                <!-- Prodotto -->
                <div>
                    <label for="product_id" class="block text-sm font-medium mb-2">Prodotto *</label>
                    <select name="product_id" id="product_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                        <option value="">Seleziona Prodotto</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->codice }} - {{ $product->nome_commerciale }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Lotto -->
                <div>
                    <label for="lotto" class="block text-sm font-medium mb-2">Lotto</label>
                    <input type="text" name="lotto" id="lotto" value="{{ old('lotto') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                </div>
            </div>

            <!-- Quantità per Categoria -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">Quantità per Categoria (kg)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="kg_reimmersione" class="block text-sm font-medium mb-2">Reimmersione (kg)</label>
                        <input type="number" step="0.01" name="kg_reimmersione" id="kg_reimmersione" value="{{ old('kg_reimmersione', 0) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                    </div>

                    <div>
                        <label for="kg_piccola" class="block text-sm font-medium mb-2">Piccola (kg)</label>
                        <input type="number" step="0.01" name="kg_piccola" id="kg_piccola" value="{{ old('kg_piccola', 0) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                    </div>

                    <div>
                        <label for="kg_media" class="block text-sm font-medium mb-2">Media (kg)</label>
                        <input type="number" step="0.01" name="kg_media" id="kg_media" value="{{ old('kg_media', 0) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                    </div>

                    <div>
                        <label for="kg_grossa" class="block text-sm font-medium mb-2">Grossa (kg)</label>
                        <input type="number" step="0.01" name="kg_grossa" id="kg_grossa" value="{{ old('kg_grossa', 0) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                    </div>

                    <div>
                        <label for="kg_granchio" class="block text-sm font-medium mb-2">Granchio (kg)</label>
                        <input type="number" step="0.01" name="kg_granchio" id="kg_granchio" value="{{ old('kg_granchio', 0) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                    </div>
                </div>
            </div>

            <!-- Provenienza/Destinazione -->
            <div>
                <label for="provenienza_destinazione" class="block text-sm font-medium mb-2">Provenienza/Destinazione</label>
                <input type="text" name="provenienza_destinazione" id="provenienza_destinazione" value="{{ old('provenienza_destinazione') }}"
                    placeholder="es. Zona 006FE156 o Cliente XYZ"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
            </div>

            <!-- Documento di Trasporto -->
            <div>
                <label for="transport_document_id" class="block text-sm font-medium mb-2">Documento di Trasporto (DDT)</label>
                <select name="transport_document_id" id="transport_document_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">
                    <option value="">Nessun documento collegato</option>
                    @foreach($transportDocuments as $doc)
                        <option value="{{ $doc->id }}" {{ old('transport_document_id') == $doc->id ? 'selected' : '' }}>
                            {{ $doc->serie }}{{ $doc->numero }}/{{ $doc->anno }} - {{ $doc->data_documento->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Note -->
            <div>
                <label for="note" class="block text-sm font-medium mb-2">Note</label>
                <textarea name="note" id="note" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500">{{ old('note') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4 pt-4 border-t border-gray-300 dark:border-gray-600">
                <a href="{{ route('loading-unloading.index') }}" class="px-6 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Annulla
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Crea Registrazione
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
