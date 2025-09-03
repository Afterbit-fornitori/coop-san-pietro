<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Production;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::where('company_id', auth()->user()->company_id)->get();
        return view('company.productions.index', compact('productions'));
    }

    public function create()
    {
        return view('company.productions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0',
            // altri campi necessari
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        Production::create($validated);

        return redirect()->route('production.index')
            ->with('success', 'Produzione registrata con successo.');
    }

    public function edit(Production $production)
    {
        return view('company.productions.edit', compact('production'));
    }

    public function update(Request $request, Production $production)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0',
            // altri campi necessari
        ]);

        $production->update($validated);

        return redirect()->route('production.index')
            ->with('success', 'Produzione aggiornata con successo.');
    }
}
