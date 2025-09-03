<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        // Spatie Multi-tenancy gestisce automaticamente lo scoping
        $members = Member::all();
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tax_code' => 'required|string|size:16|unique:members',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'rpm_code' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'business_name' => 'required|string|max:255',
            'plant_location' => 'required|string|max:255',
            'rpm_notes' => 'nullable|string',
            'vessel_notes' => 'nullable|string'
        ]);

        // Spatie Multi-tenancy imposterà automaticamente il company_id
        $member = Member::create($validated);

        // Genera automaticamente la struttura di produzione settimanale
        // TODO: Implementare la logica per la generazione della struttura di produzione

        return redirect()->route('members.index')
            ->with('success', 'Socio creato con successo.');
    }

    public function edit(Member $member)
    {
        $this->authorize('update', $member);
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $this->authorize('update', $member);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tax_code' => 'required|string|size:16|unique:members,tax_code,' . $member->id,
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'rpm_code' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'business_name' => 'required|string|max:255',
            'plant_location' => 'required|string|max:255',
            'rpm_notes' => 'nullable|string',
            'vessel_notes' => 'nullable|string'
        ]);

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Socio aggiornato con successo.');
    }

    public function destroy(Member $member)
    {
        $this->authorize('delete', $member);
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Socio eliminato con successo.');
    }
}
