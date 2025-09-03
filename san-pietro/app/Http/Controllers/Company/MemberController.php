<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::where('company_id', auth()->user()->company_id)->get();
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
            'email' => 'required|email|max:255',
            // altri campi necessari
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Membro creato con successo.');
    }

    public function edit(Member $member)
    {
        return view('company.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            // altri campi necessari
        ]);

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Membro aggiornato con successo.');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Membro eliminato con successo.');
    }
}
