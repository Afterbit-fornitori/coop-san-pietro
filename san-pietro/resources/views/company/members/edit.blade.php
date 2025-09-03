@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex items-center mb-6">
            <a href="{{ route('members.index') }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                ← Torna alla lista
            </a>
            <h2 class="text-2xl font-semibold">Modifica Socio: {{ $member->name }}</h2>
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
                        <div>
                            <label for="name" class="block text-sm font-medium">Nome Completo *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $member->name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
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

                <!-- Informazioni Aziendali -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">Informazioni Aziendali</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="business_name" class="block text-sm font-medium">Ragione Sociale *</label>
                            <input type="text" id="business_name" name="business_name" value="{{ old('business_name', $member->business_name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>

                        <div>
                            <label for="plant_location" class="block text-sm font-medium">Ubicazione Impianto *</label>
                            <input type="text" id="plant_location" name="plant_location" value="{{ old('plant_location', $member->plant_location) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>

                        <div>
                            <label for="rpm_code" class="block text-sm font-medium">Codice RPM *</label>
                            <input type="text" id="rpm_code" name="rpm_code" value="{{ old('rpm_code', $member->rpm_code) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>

                        <div>
                            <label for="registration_date" class="block text-sm font-medium">Data Iscrizione *</label>
                            <input type="date" id="registration_date" name="registration_date" value="{{ old('registration_date', $member->registration_date->format('Y-m-d')) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Note -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="rpm_notes" class="block text-sm font-medium">Note RPM</label>
                    <textarea id="rpm_notes" name="rpm_notes" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('rpm_notes', $member->rpm_notes) }}</textarea>
                </div>

                <div>
                    <label for="vessel_notes" class="block text-sm font-medium">Note Imbarcazioni</label>
                    <textarea id="vessel_notes" name="vessel_notes" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('vessel_notes', $member->vessel_notes) }}</textarea>
                </div>
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