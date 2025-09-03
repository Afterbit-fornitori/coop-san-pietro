@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Gestione Soci</h2>
            <a href="{{ route('members.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Nuovo Socio
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Codice Fiscale</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ragione Sociale</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Codice RPM</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Iscrizione</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($members as $member)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium">{{ $member->name }}</div>
                                <div class="text-sm text-gray-500">{{ $member->birth_place }}, {{ $member->birth_date->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $member->tax_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $member->business_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $member->rpm_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $member->registration_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('members.edit', $member) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Modifica
                                </a>
                                <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Sei sicuro di voler eliminare questo socio?')">
                                        Elimina
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection