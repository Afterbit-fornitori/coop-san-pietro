@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold">Nuovo Invito Azienda</h2>
                    <p class="text-gray-600 mt-2">Invita una nuova azienda a unirsi alla piattaforma. L'azienda riceverà un'email con le istruzioni per registrarsi.</p>
                </div>

                <form action="{{ route('company.invitations.store') }}" method="POST">
                    @csrf

                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Dati Azienda da Invitare</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-gray-700">Nome Azienda *</label>
                                <input type="text" name="company_name" id="company_name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('company_name') }}" required>
                                @error('company_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Referente *</label>
                                <input type="email" name="email" id="email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('email') }}" required>
                                <p class="mt-1 text-xs text-gray-500">L'invito verrà inviato a questo indirizzo email</p>
                                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="business_type" class="block text-sm font-medium text-gray-700">Tipo Attività *</label>
                                <select name="business_type" id="business_type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Seleziona tipo</option>
                                    <option value="Cooperativa di Pesca" {{ old('business_type') == 'Cooperativa di Pesca' ? 'selected' : '' }}>Cooperativa di Pesca</option>
                                    <option value="Cooperativa Agricola" {{ old('business_type') == 'Cooperativa Agricola' ? 'selected' : '' }}>Cooperativa Agricola</option>
                                    <option value="Società di Capitali" {{ old('business_type') == 'Società di Capitali' ? 'selected' : '' }}>Società di Capitali</option>
                                    <option value="Ditta Individuale" {{ old('business_type') == 'Ditta Individuale' ? 'selected' : '' }}>Ditta Individuale</option>
                                    <option value="Altro" {{ old('business_type') == 'Altro' ? 'selected' : '' }}>Altro</option>
                                </select>
                                @error('business_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="sector" class="block text-sm font-medium text-gray-700">Settore *</label>
                                <select name="sector" id="sector"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Seleziona settore</option>
                                    <option value="Mitilicoltura" {{ old('sector') == 'Mitilicoltura' ? 'selected' : '' }}>Mitilicoltura (Cozze)</option>
                                    <option value="Vongole" {{ old('sector') == 'Vongole' ? 'selected' : '' }}>Vongole</option>
                                    <option value="Ostriche" {{ old('sector') == 'Ostriche' ? 'selected' : '' }}>Ostriche</option>
                                    <option value="Pesca" {{ old('sector') == 'Pesca' ? 'selected' : '' }}>Pesca</option>
                                    <option value="Molluschicoltura" {{ old('sector') == 'Molluschicoltura' ? 'selected' : '' }}>Molluschicoltura</option>
                                    <option value="Altro" {{ old('sector') == 'Altro' ? 'selected' : '' }}>Altro</option>
                                </select>
                                @error('sector')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Permessi</h3>
                        <p class="text-sm text-gray-600 mb-4">Seleziona quali funzionalità l'azienda invitata potrà utilizzare:</p>

                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="members" id="perm_members"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    {{ in_array('members', old('permissions', [])) ? 'checked' : '' }}>
                                <label for="perm_members" class="ml-2 block text-sm text-gray-700">
                                    <span class="font-medium">Gestione Soci</span>
                                    <span class="text-gray-500 block text-xs">Creare e gestire i soci della cooperativa</span>
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="productions" id="perm_productions"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    {{ in_array('productions', old('permissions', [])) ? 'checked' : '' }}>
                                <label for="perm_productions" class="ml-2 block text-sm text-gray-700">
                                    <span class="font-medium">Registri di Produzione</span>
                                    <span class="text-gray-500 block text-xs">Inserire dati produzione settimanale e calcolo bonifici</span>
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="documents" id="perm_documents"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    {{ in_array('documents', old('permissions', [])) ? 'checked' : '' }}>
                                <label for="perm_documents" class="ml-2 block text-sm text-gray-700">
                                    <span class="font-medium">Documenti di Trasporto</span>
                                    <span class="text-gray-500 block text-xs">Generare DDT, DTN e altri documenti di trasporto</span>
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="reports" id="perm_reports"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    {{ in_array('reports', old('permissions', [])) ? 'checked' : '' }}>
                                <label for="perm_reports" class="ml-2 block text-sm text-gray-700">
                                    <span class="font-medium">Report e Statistiche</span>
                                    <span class="text-gray-500 block text-xs">Visualizzare report e statistiche produzione</span>
                                </label>
                            </div>
                        </div>
                        @error('permissions')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                        <p class="font-semibold mb-2">Si sono verificati degli errori:</p>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            Invia Invito
                        </button>
                        <a href="{{ route('company.invitations.index') }}" class="text-gray-600 hover:text-gray-900">
                            Annulla
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
