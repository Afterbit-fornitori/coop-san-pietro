@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Dettagli Cliente</h2>
            <div class="space-x-2">
                <a href="{{ route('clients.edit', $client) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Modifica
                </a>
                <a href="{{ route('clients.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Torna alla Lista
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Dati Anagrafici -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-600 pb-2">Dati Anagrafici</h3>

                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Ragione Sociale:</span>
                        <p class="text-base font-semibold">{{ $client->business_name }}</p>
                    </div>

                    @if($client->vat_number)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Partita IVA:</span>
                        <p class="text-base">{{ $client->vat_number }}</p>
                    </div>
                    @endif

                    @if($client->tax_code)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Codice Fiscale:</span>
                        <p class="text-base">{{ $client->tax_code }}</p>
                    </div>
                    @endif

                    <div>
                        <span class="text-sm font-medium text-gray-500">Stato:</span>
                        <p>
                            @if($client->is_active)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Attivo
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Inattivo
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Indirizzo -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-600 pb-2">Indirizzo</h3>

                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Indirizzo Completo:</span>
                        <p class="text-base">{{ $client->full_address }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">CAP:</span>
                            <p class="text-base">{{ $client->postal_code }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Provincia:</span>
                            <p class="text-base">{{ $client->province }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contatti -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-600 pb-2">Contatti</h3>

                <div class="space-y-3">
                    @if($client->phone)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Telefono:</span>
                        <p class="text-base">📞 {{ $client->phone }}</p>
                    </div>
                    @endif

                    @if($client->email)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Email:</span>
                        <p class="text-base">✉️ <a href="mailto:{{ $client->email }}" class="text-blue-600 hover:underline">{{ $client->email }}</a></p>
                    </div>
                    @endif

                    @if($client->pec)
                    <div>
                        <span class="text-sm font-medium text-gray-500">PEC:</span>
                        <p class="text-base">📧 <a href="mailto:{{ $client->pec }}" class="text-blue-600 hover:underline">{{ $client->pec }}</a></p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Fatturazione Elettronica -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-600 pb-2">Fatturazione Elettronica</h3>

                <div class="space-y-3">
                    @if($client->sdi_code)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Codice SDI:</span>
                        <p class="text-base font-mono">{{ $client->sdi_code }}</p>
                    </div>
                    @else
                    <p class="text-sm text-gray-500 italic">Nessun codice SDI configurato</p>
                    @endif
                </div>
            </div>

            <!-- Note -->
            @if($client->note)
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg md:col-span-2">
                <h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-600 pb-2">Note</h3>
                <p class="text-base whitespace-pre-line">{{ $client->note }}</p>
            </div>
            @endif

            <!-- Documenti di Trasporto Collegati -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg md:col-span-2">
                <h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-600 pb-2">Documenti di Trasporto</h3>

                @if($client->transportDocuments && $client->transportDocuments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-800">
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Numero</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Totale</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach($client->transportDocuments->take(10) as $document)
                            <tr>
                                <td class="px-4 py-2">{{ $document->document_number }}</td>
                                <td class="px-4 py-2">{{ $document->document_date?->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $document->document_type }}</td>
                                <td class="px-4 py-2">€ {{ number_format($document->total_amount ?? 0, 2, ',', '.') }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('transport-documents.show', $document) }}" class="text-blue-600 hover:underline">Dettagli</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($client->transportDocuments->count() > 10)
                    <p class="mt-2 text-sm text-gray-500">Mostrati 10 di {{ $client->transportDocuments->count() }} documenti</p>
                    @endif
                </div>
                @else
                <p class="text-sm text-gray-500 italic">Nessun documento di trasporto associato</p>
                @endif
            </div>

            <!-- Info Aggiornamenti -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg md:col-span-2">
                <h3 class="text-lg font-semibold mb-4 border-b border-gray-300 dark:border-gray-600 pb-2">Informazioni Sistema</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Creato il:</span>
                        <p class="text-base">{{ $client->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Ultimo aggiornamento:</span>
                        <p class="text-base">{{ $client->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Azienda:</span>
                        <p class="text-base">{{ $client->company->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-between border-t border-gray-300 dark:border-gray-600 pt-4">
            <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo cliente? Questa azione non può essere annullata.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Elimina Cliente
                </button>
            </form>

            <a href="{{ route('clients.edit', $client) }}" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Modifica Cliente
            </a>
        </div>
    </div>
</div>
@endsection
