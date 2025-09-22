@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex items-center mb-6">
            <a href="{{ route('members.index') }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                ← Torna alla lista
            </a>
            <h2 class="text-2xl font-semibold">Modifica Socio: {{ $member->full_name }}</h2>
        </div>

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-300 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('members.update', $member) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informazioni Personali -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">Informazioni Personali</h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="last_name" class="block text-sm font-medium">Cognome *</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $member->last_name) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>
                            <div>
                                <label for="first_name" class="block text-sm font-medium">Nome *</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $member->first_name) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       required>
                            </div>
                        </div>

                        <div>
                            <label for="tax_code" class="block text-sm font-medium">Codice Fiscale *</label>
                            <input type="text" id="tax_code" name="tax_code" value="{{ old('tax_code', $member->tax_code) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   maxlength="16" required>
                        </div>

                        <div>
                            <label for="birth_date" class="block text-sm font-medium">Data di Nascita *</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $member->birth_date->format('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>

                        <div>
                            <label for="birth_place" class="block text-sm font-medium">Luogo di Nascita *</label>
                            <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place', $member->birth_place) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Informazioni RPM -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">Informazioni RPM</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="rpm_registration" class="block text-sm font-medium">Matricola RPM *</label>
                            <input type="text" id="rpm_registration" name="rpm_registration" value="{{ old('rpm_registration', $member->rpm_registration) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="Es. RA5853" required>
                        </div>

                        <div>
                            <label for="rpm_registration_date" class="block text-sm font-medium">Data Iscrizione RPM *</label>
                            <input type="date" id="rpm_registration_date" name="rpm_registration_date" value="{{ old('rpm_registration_date', $member->rpm_registration_date->format('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium">Telefono</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $member->phone) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $member->email) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attivazione -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <div class="flex items-center">
                    <input type="checkbox" id="active" name="active" value="1" {{ old('active', $member->active) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="active" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                        Socio attivo
                    </label>
                </div>
                <p class="mt-1 text-sm text-gray-500">Il socio può partecipare alle attività della cooperativa</p>
            </div>

            <!-- Pulsanti -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('members.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Annulla
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Aggiorna Socio
                </button>
            </div>
        </form>
    </div>
</div>
@endsection