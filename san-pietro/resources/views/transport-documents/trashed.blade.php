@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Cestino Documenti di Trasporto</h2>
                    <a href="{{ route('transport-documents.index') }}"
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        ← Torna ai DDT
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Eliminato il</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($trashedDocuments as $doc)
                                <tr class="bg-red-50">
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
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
                                        {{ $doc->client->ragione_sociale ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($doc->deleted_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm space-x-2">
                                        <form action="{{ route('transport-documents.restore', $doc->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:underline" onclick="return confirm('Ripristinare questo documento?')">
                                                Ripristina
                                            </button>
                                        </form>
                                        <form action="{{ route('transport-documents.force-destroy', $doc->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Eliminare DEFINITIVAMENTE questo documento? Questa azione è irreversibile!')">
                                                Elimina Definitivamente
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Nessun documento nel cestino.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $trashedDocuments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
