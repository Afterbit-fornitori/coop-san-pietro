<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\Member;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::forCurrentCompany()
            ->with('member')
            ->get();
        return view('production.index', compact('productions'));
    }

    public function create()
    {
        $members = Member::forCurrentCompany()->get();
        $currentWeek = now()->week;
        $currentYear = now()->year;
        
        return view('production.create', compact('members', 'currentWeek', 'currentYear'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'week_number' => 'required|integer|between:1,53',
            'year' => 'required|integer',
            'micro_price' => 'required|numeric|min:0',
            'micro_quantity' => 'required|numeric|min:0',
            'standard_price' => 'required|numeric|min:0',
            'standard_quantity' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $member = Member::findOrFail($validated['member_id']);
        $this->authorize('create', [Production::class, $member]);

        $production = Production::create($validated + [
            'company_id' => auth()->user()->company_id
        ]);

        return redirect()->route('production.index')
            ->with('success', 'Produzione settimanale registrata con successo.');
    }

    public function edit(Production $production)
    {
        $this->authorize('update', $production);
        $members = Member::forCurrentCompany()->get();
        
        return view('production.edit', compact('production', 'members'));
    }

    public function update(Request $request, Production $production)
    {
        $this->authorize('update', $production);

        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'week_number' => 'required|integer|between:1,53',
            'year' => 'required|integer',
            'micro_price' => 'required|numeric|min:0',
            'micro_quantity' => 'required|numeric|min:0',
            'standard_price' => 'required|numeric|min:0',
            'standard_quantity' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $production->update($validated);

        return redirect()->route('production.index')
            ->with('success', 'Produzione settimanale aggiornata con successo.');
    }
}
