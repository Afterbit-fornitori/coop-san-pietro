@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Documenti di Trasporto (DDT/DTN/DDR)</h2>
                    <a href="{{ route('transport-documents.create') }}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        + Nuovo DDT
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Numero</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Causale</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transportDocuments as $doc)
                                <tr>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                            {{ $doc->tipo_documento }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $doc->serie }}/{{ str_pad($doc->numero, 4, '0', STR_PAD_LEFT) }}/{{ $doc->anno }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($doc->data_documento)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $doc->client->business_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $doc->causale_trasporto }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('transport-documents.show', $doc) }}" class="text-blue-600 hover:underline mr-2">Vedi</a>
                                        <a href="{{ route('transport-documents.pdf.view', $doc) }}" target="_blank" class="text-green-600 hover:underline mr-2">PDF</a>
                                        <a href="{{ route('transport-documents.edit', $doc) }}" class="text-indigo-600 hover:underline">Modifica</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Nessun documento trovato. <a href="{{ route('transport-documents.create') }}" class="text-blue-600 hover:underline">Crea il primo DDT</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transportDocuments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
