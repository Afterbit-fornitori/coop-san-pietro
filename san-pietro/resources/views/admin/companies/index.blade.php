@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Gestione Aziende</h2>
                        @if(auth()->user()->hasRole('super-admin') || (auth()->user()->hasRole('company-admin') && auth()->user()->company->domain === 'san-pietro.test'))
                            <a href="{{ route('admin.companies.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Nuova Azienda
                            </a>
                        @endif
                    </div>                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-600">
                            <tr>
                                <th class="py-3 px-4 text-left">Nome</th>
                                <th class="py-3 px-4 text-left">Dominio</th>
                                <th class="py-3 px-4 text-left">Tipo</th>
                                <th class="py-3 px-4 text-left">Utenti</th>
                                <th class="py-3 px-4 text-left">Stato</th>
                                <th class="py-3 px-4 text-left">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-500">
                            @foreach($companies as $company)
                            <tr>
                                <td class="py-3 px-4">{{ $company->name }}</td>
                                <td class="py-3 px-4">{{ $company->domain }}</td>
                                <td class="py-3 px-4">{{ ucfirst($company->type) }}</td>
                                <td class="py-3 px-4">{{ $company->users->count() }}</td>
                                <td class="py-3 px-4">
                                    @if($company->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Attiva
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inattiva
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-3">
                                        @if(auth()->user()->hasRole('super-admin') || 
                                            (auth()->user()->hasRole('company-admin') && auth()->user()->company->domain === 'san-pietro.test') || 
                                            (auth()->user()->hasRole('company-admin') && auth()->user()->company_id === $company->id))
                                            <a href="{{ route('admin.companies.edit', $company) }}" 
                                               class="text-indigo-600 hover:text-indigo-900">
                                                Modifica
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.companies.destroy', $company) }}" 
                                              method="POST" 
                                              class="inline-block"
                                              onsubmit="return confirm('Sei sicuro di voler eliminare questa azienda?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900">
                                                Elimina
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
