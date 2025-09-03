@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex items-center mb-6">
            <a href="{{ route('documents.index') }}" class="text-indigo-600 hover:text-indigo-900 mr-4">
                ← Torna alla lista
            </a>
            <h2 class="text-2xl font-semibold">Nuovo DDT/Documento</h2>
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

        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informazioni Documento -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">Dettagli Documento</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium">Titolo *</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium">Tipo Documento *</label>
                            <select id="type" name="type" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">Seleziona tipo...</option>
                                <option value="ddt" {{ old('type') == 'ddt' ? 'selected' : '' }}>DDT</option>
                                <option value="invoice" {{ old('type') == 'invoice' ? 'selected' : '' }}>Fattura</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Altro</option>
                            </select>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium">Descrizione</label>
                            <textarea id="description" name="description" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Upload File -->
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">File</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="file" class="block text-sm font-medium">Carica File *</label>
                            <input type="file" id="file" name="file" 
                                   class="mt-1 block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100"
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">
                                Formati supportati: PDF, DOC, DOCX, JPG, JPEG, PNG, XLS, XLSX. Max 10MB.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pulsanti -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('documents.index') }}" 
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Annulla
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Carica Documento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection