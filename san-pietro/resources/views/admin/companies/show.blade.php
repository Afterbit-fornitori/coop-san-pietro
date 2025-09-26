@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Dettagli Azienda: {{ $company->name }}</h2>
                    <div class="space-x-2">
                        <a href="{{ route('admin.companies.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Indietro
                        </a>
                        <a href="{{ route('admin.companies.edit', $company) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Modifica
                        </a>
                    </div>
                </div>

                <!-- Company Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Informazioni Azienda -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informazioni Azienda</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nome</label>
                                    <p class="mt-1 text-sm text-gray-900 font-medium">{{ $company->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Dominio</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $company->domain }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tipo</label>
                                    <p class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($company->type === 'master') bg-purple-100 text-purple-800
                                            @elseif($company->type === 'main') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($company->type) }}
                                        </span>
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stato</label>
                                    <p class="mt-1">
                                        @if($company->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Attiva
                                        </span>
                                        @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inattiva
                                        </span>
                                        @endif
                                    </p>
                                </div>

                                @if($company->parent_company_id && $company->parentCompany)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Azienda Madre</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        <a href="{{ route('admin.companies.show', $company->parentCompany) }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $company->parentCompany->name }}
                                        </a>
                                    </p>
                                </div>
                                @endif

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Data Creazione</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $company->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informazioni Fiscali -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informazioni Fiscali</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Partita IVA</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $company->vat_number ?? 'Non specificata' }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Codice Fiscale</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $company->tax_code ?? 'Non specificato' }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Indirizzo</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $company->address ?? 'Non specificato' }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Città</label>
                                    <p class="mt-1 text-sm text-gray-900">
                                        {{ $company->city ?? 'Non specificata' }}
                                        @if($company->province), {{ $company->province }}@endif
                                        @if($company->zip_code) - {{ $company->zip_code }}@endif
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Contatti</label>
                                    <div class="mt-1 text-sm text-gray-900">
                                        @if($company->phone)
                                            <p>Tel: {{ $company->phone }}</p>
                                        @endif
                                        @if($company->email)
                                            <p>Email: {{ $company->email }}</p>
                                        @endif
                                        @if($company->pec)
                                            <p>PEC: {{ $company->pec }}</p>
                                        @endif
                                        @if(!$company->phone && !$company->email && !$company->pec)
                                            <p>Nessun contatto specificato</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Utenti ({{ $company->users->count() }})</h3>
                            <a href="{{ route('admin.users.create') }}?company_id={{ $company->id }}" class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded">
                                Aggiungi Utente
                            </a>
                        </div>

                        @if($company->users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruolo</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stato</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($company->users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @foreach($user->getRoleNames() as $role)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mr-1">
                                                {{ $role }}
                                            </span>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->is_active ?? true)
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
                                            <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900 mr-3">Visualizza</a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Modifica</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p class="text-gray-500 text-center py-4">Nessun utente associato a questa azienda.</p>
                        @endif
                    </div>
                </div>

                <!-- Child Companies -->
                @if($company->childCompanies->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aziende Figlie ({{ $company->childCompanies->count() }})</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($company->childCompanies as $child)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $child->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $child->domain }}</p>
                                    </div>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($child->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                        {{ $child->is_active ? 'Attiva' : 'Inattiva' }}
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('admin.companies.show', $child) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Visualizza Dettagli
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-blue-100 p-4 rounded-lg border border-blue-200">
                        <h4 class="text-sm font-medium text-blue-800">Utenti</h4>
                        <p class="text-2xl font-bold text-blue-900">{{ $company->users->count() }}</p>
                    </div>

                    <div class="bg-green-100 p-4 rounded-lg border border-green-200">
                        <h4 class="text-sm font-medium text-green-800">Membri</h4>
                        <p class="text-2xl font-bold text-green-900">{{ $company->members->count() ?? 0 }}</p>
                    </div>

                    <div class="bg-purple-100 p-4 rounded-lg border border-purple-200">
                        <h4 class="text-sm font-medium text-purple-800">Produzioni</h4>
                        <p class="text-2xl font-bold text-purple-900">{{ $company->productions->count() ?? 0 }}</p>
                    </div>

                    <div class="bg-yellow-100 p-4 rounded-lg border border-yellow-200">
                        <h4 class="text-sm font-medium text-yellow-800">Documenti</h4>
                        <p class="text-2xl font-bold text-yellow-900">{{ $company->transportDocuments->count() ?? 0 }}</p>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-red-800 mb-4">Zona Pericolosa</h3>

                    <div class="flex space-x-4">
                        <form action="{{ route('admin.companies.toggle-status', $company->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition duration-150"
                                onclick="return confirm('Sei sicuro di voler {{ $company->is_active ? 'disattivare' : 'attivare' }} questa azienda?')">
                                {{ $company->is_active ? 'Disattiva Azienda' : 'Attiva Azienda' }}
                            </button>
                        </form>

                        @if(auth()->user()->hasRole('SUPER_ADMIN'))
                        <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition duration-150"
                                onclick="return confirm('ATTENZIONE: Sei sicuro di voler eliminare questa azienda? Tutti i dati associati (utenti, membri, produzioni) saranno eliminati permanentemente. Questa azione non può essere annullata.')">
                                Elimina Azienda
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection