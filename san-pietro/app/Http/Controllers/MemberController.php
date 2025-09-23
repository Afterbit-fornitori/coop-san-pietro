<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Member::class, 'member');
    }
    public function index()
    {
        // Spatie Multi-tenancy gestisce automaticamente lo scoping
        $members = Member::orderBy('cognome')->orderBy('nome')->paginate(15);
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'tax_code' => 'required|string|size:16|unique:members,tax_code',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'rpm_registration' => 'required|string|max:255',
            'rpm_registration_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'nullable|boolean'
        ]);

        // Assicura che is_active sia impostato correttamente
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Spatie Multi-tenancy imposterà automaticamente il company_id
        $validated['company_id'] = auth()->user()->company_id;
        $member = Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Socio creato con successo.');
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'tax_code' => 'required|string|size:16|unique:members,tax_code,' . $member->id,
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:255',
            'rpm_registration' => 'required|string|max:255',
            'rpm_registration_date' => 'required|date',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'nullable|boolean'
        ]);

        // Assicura che is_active sia impostato correttamente
        $validated['is_active'] = $request->has('is_active') ? true : false;

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
