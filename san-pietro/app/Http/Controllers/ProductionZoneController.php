<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductionZone;
use Illuminate\Http\Request;

class ProductionZoneController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ProductionZone::class, 'production_zone');
    }
    public function index()
    {
        // Spatie Multi-tenancy gestisce automaticamente lo scoping
        $zones = ProductionZone::orderBy('nome')->paginate(15);
        return view('production-zones.index', compact('zones'));
    }

    public function create()
    {
        return view('production-zones.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codice' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
            'mq' => 'nullable|numeric|min:0',
            'classe_sanitaria' => 'nullable|in:A,B,C',
            'declassificazione_temporanea' => 'nullable|boolean',
            'data_declassificazione' => 'nullable|date|required_if:declassificazione_temporanea,true',
            'is_active' => 'nullable|boolean'
        ]);

        // Assicura che is_active sia impostato correttamente
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Spatie Multi-tenancy imposterà automaticamente il company_id
        $validated['company_id'] = auth()->user()->company_id;
        ProductionZone::create($validated);

        return redirect()->route('production-zones.index')
            ->with('success', 'Zona di produzione creata con successo.');
    }

    public function show(ProductionZone $zone)
    {
        return view('production-zones.show', compact('zone'));
    }

    public function edit(ProductionZone $zone)
    {
        return view('production-zones.edit', compact('zone'));
    }

    public function update(Request $request, ProductionZone $zone)
    {
        $validated = $request->validate([
            'codice' => 'required|string|max:255',
            'nome' => 'required|string|max:255',
            'mq' => 'nullable|numeric|min:0',
            'classe_sanitaria' => 'nullable|in:A,B,C',
            'declassificazione_temporanea' => 'nullable|boolean',
            'data_declassificazione' => 'nullable|date|required_if:declassificazione_temporanea,true',
            'is_active' => 'nullable|boolean'
        ]);

        // Assicura che is_active sia impostato correttamente
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $zone->update($validated);

        return redirect()->route('production-zones.index')
            ->with('success', 'Zona di produzione aggiornata con successo.');
    }

    public function destroy(ProductionZone $zone)
    {
        $zone->delete();

        return redirect()->route('production-zones.index')
            ->with('success', 'Zona di produzione eliminata con successo.');
    }
}
