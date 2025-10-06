@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex items-center mb-6">
                    <a href="{{ route('transport-documents.index') }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                        ← Torna alla lista
                    </a>
                    <h2 class="text-2xl font-semibold">Nuovo Documento di Trasporto</h2>
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

                <form action="{{ route('transport-documents.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Tipo Documento -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Tipo Documento e Serie</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo *</label>
                                <select name="tipo_documento" class="mt-1 block w-full rounded-md border-gray-300" required>
                                    <option value="DDT">DDT</option>
                                    <option value="DTN">DTN</option>
                                    <option value="DDR">DDR</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Serie *</label>
                                <input type="text" name="serie" value="{{ old('serie', 'CSP') }}" class="mt-1 block w-full rounded-md border-gray-300" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Data *</label>
                                <input type="date" name="data_documento" value="{{ old('data_documento', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300" required>
                            </div>
                        </div>
                    </div>

                    <!-- Cliente -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Cliente</h3>
                        <select name="client_id" class="block w-full rounded-md border-gray-300" required>
                            <option value="">Seleziona cliente...</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->business_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Trasporto -->
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">Dettagli Trasporto</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Causale *</label>
                                <input type="text" name="causale_trasporto" value="Vendita" class="mt-1 block w-full rounded-md border-gray-300" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Mezzo</label>
                                <input type="text" name="mezzo_trasporto" class="mt-1 block w-full rounded-md border-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Zona Produzione</label>
                                <select name="production_zone_id" class="block w-full rounded-md border-gray-300">
                                    <option value="">Nessuna</option>
                                    @foreach($productionZones as $zone)
                                        <option value="{{ $zone->id }}">{{ $zone->codice }} - {{ $zone->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Socio</label>
                                <select name="member_id" class="block w-full rounded-md border-gray-300">
                                    <option value="">Nessuno</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('transport-documents.index') }}" class="px-6 py-2 bg-gray-300 rounded">Annulla</a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded">Crea Documento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
