@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold">Nuova Azienda</h2>
                </div>

                <form action="{{ route('admin.companies.store') }}" method="POST">
                    @csrf

                    <!-- Dati Azienda -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Dati Azienda</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nome Azienda *</label>
                                <input type="text" name="name" id="name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('name') }}" required>
                                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="domain" class="block text-sm font-medium text-gray-700">Dominio *</label>
                                <input type="text" name="domain" id="domain"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('domain') }}" required>
                                @error('domain')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Tipo *</label>
                                <select name="type" id="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Seleziona Tipo</option>
                                    @if(in_array('master', $allowedTypes ?? []))
                                    <option value="master" {{ old('type') == 'master' ? 'selected' : '' }}>Master</option>
                                    @endif
                                    @if(in_array('main', $allowedTypes ?? []))
                                    <option value="main" {{ old('type') == 'main' ? 'selected' : '' }}>Main (Cooperativa Principale)</option>
                                    @endif
                                    @if(in_array('invited', $allowedTypes ?? []))
                                    <option value="invited" {{ old('type') == 'invited' ? 'selected' : '' }}>Invited (Cooperativa Invitata)</option>
                                    @endif
                                </select>
                                @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="parent_company_id" class="block text-sm font-medium text-gray-700">
                                    Azienda Padre
                                    <span class="text-gray-500 text-xs">(opzionale)</span>
                                </label>
                                <select name="parent_company_id" id="parent_company_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Nessuna (azienda indipendente)</option>
                                    @foreach($parentCompanies as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_company_id') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('parent_company_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="business_type" class="block text-sm font-medium text-gray-700">Tipo Attività</label>
                                <select name="business_type" id="business_type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleziona tipo attività</option>
                                    <option value="Cooperativa di Pesca" {{ old('business_type') == 'Cooperativa di Pesca' ? 'selected' : '' }}>Cooperativa di Pesca</option>
                                    <option value="Cooperativa Agricola" {{ old('business_type') == 'Cooperativa Agricola' ? 'selected' : '' }}>Cooperativa Agricola</option>
                                    <option value="Società di Capitali" {{ old('business_type') == 'Società di Capitali' ? 'selected' : '' }}>Società di Capitali</option>
                                    <option value="Ditta Individuale" {{ old('business_type') == 'Ditta Individuale' ? 'selected' : '' }}>Ditta Individuale</option>
                                    <option value="Altro" {{ old('business_type') == 'Altro' ? 'selected' : '' }}>Altro</option>
                                </select>
                                @error('business_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="sector" class="block text-sm font-medium text-gray-700">Settore</label>
                                <select name="sector" id="sector"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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

                            <div>
                                <label for="vat_number" class="block text-sm font-medium text-gray-700">Partita IVA</label>
                                <input type="text" name="vat_number" id="vat_number"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('vat_number') }}" maxlength="11">
                                @error('vat_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="tax_code" class="block text-sm font-medium text-gray-700">Codice Fiscale</label>
                                <input type="text" name="tax_code" id="tax_code"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('tax_code') }}" maxlength="16">
                                @error('tax_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Indirizzo</label>
                                <input type="text" name="address" id="address"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('address') }}">
                                @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">Città</label>
                                <input type="text" name="city" id="city"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('city') }}">
                                @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-700">Provincia</label>
                                <input type="text" name="province" id="province"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('province') }}" maxlength="2">
                                @error('province')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="zip_code" class="block text-sm font-medium text-gray-700">CAP</label>
                                <input type="text" name="zip_code" id="zip_code"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('zip_code') }}" maxlength="10">
                                @error('zip_code')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Telefono</label>
                                <input type="text" name="phone" id="phone"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('phone') }}">
                                @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('email') }}">
                                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="pec" class="block text-sm font-medium text-gray-700">PEC</label>
                                <input type="email" name="pec" id="pec"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('pec') }}">
                                @error('pec')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">Azienda Attiva</label>
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                            Crea Azienda
                        </button>
                        <a href="{{ route('admin.companies.index') }}" class="text-gray-600 hover:text-gray-900">
                            Annulla
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection