<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        // Spatie Multi-tenancy gestisce automaticamente lo scoping
        $members = Member::all();
        return view('company.members.index', compact('members'));
    }

    public function create()
    {
        return view('company.members.create');
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

        return redirect()->route('members.index')
            ->with('success', 'Socio creato con successo.');
    }

    public function edit(Member $member)
    {
        return view('company.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
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
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Socio eliminato con successo.');
    }
}
