<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Client::class, 'client');
    }
    public function index()
    {
        $clients = Client::orderBy('ragione_sociale')->paginate(15);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ragione_sociale' => 'required|string|max:255',
            'indirizzo' => 'required|string|max:255',
            'cap' => 'required|string|max:10',
            'comune' => 'required|string|max:255',
            'provincia' => 'required|string|max:5',
            'nazione' => 'required|string|max:100|default:Italia',
            'partita_iva' => 'nullable|string|max:20',
            'codice_fiscale' => 'nullable|string|max:16',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pec' => 'nullable|email|max:255',
            'codice_sdi' => 'nullable|string|max:10',
            'note' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Assicura che is_active sia impostato correttamente
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $validated['company_id'] = auth()->user()->company_id;
        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente creato con successo.');
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'ragione_sociale' => 'required|string|max:255',
            'indirizzo' => 'required|string|max:255',
            'cap' => 'required|string|max:10',
            'comune' => 'required|string|max:255',
            'provincia' => 'required|string|max:5',
            'nazione' => 'required|string|max:100',
            'partita_iva' => 'nullable|string|max:20',
            'codice_fiscale' => 'nullable|string|max:16',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pec' => 'nullable|email|max:255',
            'codice_sdi' => 'nullable|string|max:10',
            'note' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Assicura che is_active sia impostato correttamente
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente aggiornato con successo.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Cliente eliminato con successo.');
    }
}
