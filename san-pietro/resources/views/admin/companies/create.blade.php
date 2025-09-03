@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold">Nuova Azienda</h2>
                </div>

                <form action="{{ route('admin.companies.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nome Azienda
                            </label>
                            <input type="text"
                                name="name"
                                id="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                value="{{ old('name') }}"
                                required>
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Dominio
                            </label>
                            <input type="text"
                                name="domain"
                                id="domain"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                value="{{ old('domain') }}"
                                required>
                            @error('domain')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tipo
                            </label>
                            <select name="type"
                                id="type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
                                required>
                                <option value="parent" {{ old('type') == 'parent' ? 'selected' : '' }}>Parent</option>
                                <option value="child" {{ old('type') == 'child' ? 'selected' : '' }}>Child</option>
                            </select>
                            @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- <div class="parent-company-field" style="{{ old('type') == 'child' ? '' : 'display: none;' }}"> -->
                        <div class="parent-company-field" @if(old('type') !='child' ) style="display: none;" @endif>
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Azienda Padre
                            </label>
                            <select name="parent_id"
                                id="parent_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                                <option value="">Seleziona azienda padre</option>
                                @foreach(\App\Models\Company::where('type', 'parent')->get() as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Crea Azienda
                        </button>
                        <a href="{{ route('admin.companies.index') }}" class="ml-3 text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                            Annulla
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('type').addEventListener('change', function() {
        const parentField = document.querySelector('.parent-company-field');
        if (this.value === 'child') {
            parentField.style.display = '';
        } else {
            parentField.style.display = 'none';
        }
    });
</script>
@endpush
@endsection