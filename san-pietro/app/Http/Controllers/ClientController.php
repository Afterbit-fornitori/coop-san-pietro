<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Client::class, 'client');
    }

    public function index()
    {
        $clients = Client::orderBy('business_name')->paginate(15);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:2',
            'vat_number' => [
                'nullable',
                'string',
                'max:11',
                Rule::unique('clients')->where(fn($q) =>
                    $q->where('company_id', auth()->user()->company_id)
                )
            ],
            'tax_code' => [
                'nullable',
                'string',
                'max:16',
                Rule::unique('clients')->where(fn($q) =>
                    $q->where('company_id', auth()->user()->company_id)
                )
            ],
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pec' => 'nullable|email|max:255',
            'sdi_code' => 'nullable|string|max:7',
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
            'business_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:2',
            'vat_number' => [
                'nullable',
                'string',
                'max:11',
                Rule::unique('clients')->where(fn($q) =>
                    $q->where('company_id', auth()->user()->company_id)
                )->ignore($client->id)
            ],
            'tax_code' => [
                'nullable',
                'string',
                'max:16',
                Rule::unique('clients')->where(fn($q) =>
                    $q->where('company_id', auth()->user()->company_id)
                )->ignore($client->id)
            ],
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pec' => 'nullable|email|max:255',
            'sdi_code' => 'nullable|string|max:7',
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
