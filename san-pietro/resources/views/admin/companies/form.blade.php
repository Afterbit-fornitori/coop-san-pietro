@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-2xl font-semibold mb-6">{{ isset($company) ? 'Modifica Azienda' : 'Nuova Azienda' }}</h2>

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ isset($company) ? route('admin.companies.update', $company) : route('admin.companies.store') }}">
                    @csrf
                    @if(isset($company))
                    @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nome Azienda -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome Azienda *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $company->name ?? '') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Domain -->
                        <div>
                            <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dominio *</label>
                            <input type="text" name="domain" id="domain" value="{{ old('domain', $company->domain ?? '') }}" required
                                placeholder="es. sanpietro.local"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('domain')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipo Azienda -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo Azienda *</label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Seleziona Tipo --</option>
                                @foreach($allowedTypes as $type)
                                    <option value="{{ $type }}" {{ (old('type', $company->type ?? '') == $type) ? 'selected' : '' }}>
                                        @if($type == 'main')
                                            Main (Cooperativa Principale - San Pietro)
                                        @elseif($type == 'invited')
                                            Invited (Cooperativa Invitata)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                <strong>Main</strong>: San Pietro (proprietario piattaforma).
                                <strong>Invited</strong>: Cooperative invitate (Rosa, Mosè e B., ecc.)
                            </p>
                            @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Azienda Parent -->
                        <div>
                            <label for="parent_company_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Azienda Parent (opzionale)</label>
                            <select name="parent_company_id" id="parent_company_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Nessun Parent --</option>
                                @foreach($parentCompanies as $parent)
                                    <option value="{{ $parent->id }}" {{ (old('parent_company_id', $company->parent_company_id ?? '') == $parent->id) ? 'selected' : '' }}>
                                        {{ $parent->name }} ({{ $parent->type }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Se l'azienda è di tipo Invited, puoi specificare chi la gestisce</p>
                            @error('parent_company_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mt-8 mb-4 border-b pb-2">Dati Fiscali</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Partita IVA -->
                        <div>
                            <label for="vat_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Partita IVA</label>
                            <input type="text" name="vat_number" id="vat_number" value="{{ old('vat_number', $company->vat_number ?? '') }}"
                                maxlength="11" placeholder="12345678901"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('vat_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Codice Fiscale -->
                        <div>
                            <label for="tax_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Codice Fiscale</label>
                            <input type="text" name="tax_code" id="tax_code" value="{{ old('tax_code', $company->tax_code ?? '') }}"
                                maxlength="16" placeholder="RSSMRA80A01H501U"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('tax_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mt-8 mb-4 border-b pb-2">Indirizzo</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Indirizzo -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Indirizzo</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $company->address ?? '') }}"
                                placeholder="Via Roma, 123"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Città -->
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Città</label>
                            <input type="text" name="city" id="city" value="{{ old('city', $company->city ?? '') }}"
                                placeholder="Ferrara"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Provincia -->
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Provincia</label>
                            <input type="text" name="province" id="province" value="{{ old('province', $company->province ?? '') }}"
                                maxlength="2" placeholder="FE"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('province')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CAP -->
                        <div>
                            <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">CAP</label>
                            <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code', $company->zip_code ?? '') }}"
                                maxlength="5" placeholder="44100"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('zip_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mt-8 mb-4 border-b pb-2">Contatti</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $company->email ?? '') }}"
                                placeholder="info@sanpietro.it"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <p class="mt-1 text-xs text-gray-500">⚠️ Per aziende Invited, verrà inviato automaticamente l'invito a questa email</p>
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PEC -->
                        <div>
                            <label for="pec" class="block text-sm font-medium text-gray-700 dark:text-gray-300">PEC</label>
                            <input type="email" name="pec" id="pec" value="{{ old('pec', $company->pec ?? '') }}"
                                placeholder="sanpietro@pec.it"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('pec')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefono -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefono</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone', $company->phone ?? '') }}"
                                placeholder="+39 0532 123456"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mt-8 mb-4 border-b pb-2">Stato</h3>

                    <!-- Azienda Attiva -->
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $company->is_active ?? true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                            Azienda Attiva
                        </label>
                    </div>

                    <!-- Pulsanti -->
                    <div class="flex justify-end mt-8 pt-6 border-t space-x-4">
                        <a href="{{ route('admin.companies.index') }}"
                            class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Annulla
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            {{ isset($company) ? 'Aggiorna Azienda' : 'Crea Azienda' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
