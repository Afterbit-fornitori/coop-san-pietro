@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-2xl font-semibold mb-6">{{ isset($company) ? 'Modifica Azienda' : 'Nuova Azienda' }}</h2>

                <form method="POST" action="{{ isset($company) ? route('admin.companies.update', $company) : route('admin.companies.store') }}">
                    @csrf
                    @if(isset($company))
                    @method('PUT')
                    @endif

                    <!-- Nome Azienda -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome Azienda</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $company->name ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Domain -->
                    <div class="mb-4">
                        <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dominio</label>
                        <input type="text" name="domain" id="domain" value="{{ old('domain', $company->domain ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('domain')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Partita IVA -->
                    <div class="mb-4">
                        <label for="vat_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Partita IVA</label>
                        <input type="text" name="vat_number" id="vat_number" value="{{ old('vat_number', $company->vat_number ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('vat_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Codice Fiscale -->
                    <div class="mb-4">
                        <label for="tax_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Codice Fiscale</label>
                        <input type="text" name="tax_code" id="tax_code" value="{{ old('tax_code', $company->tax_code ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('tax_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Indirizzo -->
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Indirizzo</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $company->address ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Città -->
                    <div class="mb-4">
                        <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Città</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $company->city ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Provincia -->
                    <div class="mb-4">
                        <label for="province" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Provincia</label>
                        <input type="text" name="province" id="province" value="{{ old('province', $company->province ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('province')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CAP -->
                    <div class="mb-4">
                        <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">CAP</label>
                        <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code', $company->zip_code ?? '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('zip_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo</label>
                        <select name="type" id="type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="child" {{ (old('type', $company->type ?? '') == 'child') ? 'selected' : '' }}>Azienda Figlia</option>
                            <option value="parent" {{ (old('type', $company->type ?? '') == 'parent') ? 'selected' : '' }}>Azienda Principale</option>
                        </select>
                        @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pulsanti -->
                    <div class="flex justify-end mt-6 space-x-4">
                        <a href="{{ route('admin.companies.index') }}"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Annulla
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            {{ isset($company) ? 'Aggiorna' : 'Crea' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection