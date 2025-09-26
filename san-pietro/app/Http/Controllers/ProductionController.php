<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionZone;
use App\Models\Member;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $query = Production::with(['productionZone', 'member']);

        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('production_zone_id')) {
            $query->where('production_zone_id', $request->production_zone_id);
        }

        if ($request->filled('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        $productions = $query->latest()->paginate(10);

        $productionZones = ProductionZone::where('is_active', true)->get();
        $members = Member::where('is_active', true)->get();

        return view('productions.index', compact('productions', 'productionZones', 'members'));
    }

    public function create()
    {
        $productionZones = ProductionZone::where('is_active', true)->get();
        $members = Member::where('is_active', true)->get();
        $companies = Company::all();

        return view('productions.create', compact('productionZones', 'members', 'companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_zone_id' => 'required|exists:production_zones,id',
            'member_id' => 'required|exists:members,id',
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'company_id' => 'required|exists:companies,id'
        ]);

        Production::create($validated);

        return redirect()->route('productions.index')
            ->with('success', 'Produzione registrata con successo.');
    }

    public function show(Production $production)
    {
        $this->authorize('view', $production);

        return view('productions.show', compact('production'));
    }

    public function edit(Production $production)
    {
        $this->authorize('update', $production);

        $productionZones = ProductionZone::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();

        $members = Member::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();

        return view('productions.edit', compact('production', 'productionZones', 'members'));
    }

    public function update(Request $request, Production $production)
    {
        $this->authorize('update', $production);

        $validated = $request->validate([
            'production_zone_id' => 'required|exists:production_zones,id',
            'member_id' => 'required|exists:members,id',
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $production->update($validated);

        return redirect()->route('productions.index')
            ->with('success', 'Produzione aggiornata con successo.');
    }

    public function destroy(Production $production)
    {
        $this->authorize('delete', $production);

        $production->delete();

        return redirect()->route('productions.index')
            ->with('success', 'Produzione eliminata con successo.');
    }
}
