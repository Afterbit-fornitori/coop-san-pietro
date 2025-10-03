@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Gestione Clienti</h2>
            <a href="{{ route('clients.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Nuovo Cliente
            </a>
        </div>

        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-300 rounded">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white dark:bg-gray-700 shadow rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ragione Sociale</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Indirizzo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partita IVA / C.F.</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contatti</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                        @forelse($clients as $client)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium">{{ $client->business_name }}</div>
                                @if($client->sdi_code)
                                    <div class="text-sm text-gray-500">SDI: {{ $client->sdi_code }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm">{{ $client->address }}</div>
                                <div class="text-sm text-gray-500">{{ $client->postal_code }} {{ $client->city }} ({{ $client->province }})</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($client->vat_number)
                                    <div class="text-sm">P.IVA: {{ $client->vat_number }}</div>
                                @endif
                                @if($client->tax_code)
                                    <div class="text-sm">C.F.: {{ $client->tax_code }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($client->phone)
                                    <div class="text-sm">📞 {{ $client->phone }}</div>
                                @endif
                                @if($client->email)
                                    <div class="text-sm">✉️ {{ $client->email }}</div>
                                @endif
                                @if($client->pec)
                                    <div class="text-sm">📧 PEC: {{ $client->pec }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($client->is_active)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Attivo
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Inattivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('clients.show', $client) }}" class="text-green-600 hover:text-green-900 mr-3">
                                    Dettagli
                                </a>
                                <a href="{{ route('clients.edit', $client) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Modifica
                                </a>
                                <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Sei sicuro di voler eliminare questo cliente?')">
                                        Elimina
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Nessun cliente trovato. <a href="{{ route('clients.create') }}" class="text-blue-600 hover:text-blue-800">Creane uno nuovo</a>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection