@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Gestione Utenti</h2>
                    <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Nuovo Utente
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded-lg overflow-hidden">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left">Nome</th>
                                <th class="py-3 px-4 text-left">Email</th>
                                <th class="py-3 px-4 text-left">Azienda</th>
                                <th class="py-3 px-4 text-left">Ruolo</th>
                                <th class="py-3 px-4 text-left">Azioni</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr>
                                <td class="py-3 px-4">{{ $user->name }}</td>
                                <td class="py-3 px-4">{{ $user->email }}</td>
                                <td class="py-3 px-4">{{ $user->company ? $user->company->name : 'Nessuna azienda' }}</td>
                                <td class="py-3 px-4">
                                    @foreach($user->roles as $role)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $role->name }}
                                    </span>
                                    @endforeach
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            Modifica
                                        </a>

                                        <form action="{{ route('admin.users.toggle-status', $user) }}"
                                            method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            @if($user->is_active)
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                                    Disattiva
                                                </button>
                                            @else
                                                <button type="submit" class="text-green-600 hover:text-green-900">
                                                    Attiva
                                                </button>
                                            @endif
                                        </form>

                                        <form action="{{ route('admin.users.destroy', $user) }}"
                                            method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Sei sicuro di voler eliminare questo utente?');">
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