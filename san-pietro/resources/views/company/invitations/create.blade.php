@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crea un nuovo invito</h1>

    <form action="{{ route('company.invitations.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Ruolo</label>
            <select class="form-select" id="role" name="role" required>
                <option value="">Seleziona un ruolo</option>
                <option value="member">Membro</option>
                <option value="admin">Amministratore</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Invia invito</button>
    </form>
</div>
@endsection