<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
    {
        // $user = User::find(auth()->id());
        $user = User::find(Auth::id());
        
        if ($user->hasRole('super-admin')) {
            $companies = Company::with('users')->get();
        } elseif ($user->hasRole('company-admin') && $user->company->domain === 'san-pietro.test') {
            $companies = Company::with('users')->get();
        } else {
            $companies = Company::with('users')
                ->where('id', $user->company_id)
                ->where('is_active', true)
                ->get();
        }

        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:companies',
            'type' => 'required|string|in:parent,child',
            'parent_id' => 'nullable|exists:companies,id',
            'settings' => 'nullable|json',
            'is_active' => 'boolean'
        ]);

        Company::create($validated);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Azienda creata con successo.');
    }

    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:companies,domain,' . $company->id,
            'type' => 'required|string|in:parent,child',
            'parent_id' => 'nullable|exists:companies,id',
            'settings' => 'nullable|json',
            'is_active' => 'boolean'
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Azienda aggiornata con successo.');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', 'Azienda eliminata con successo.');
    }

    public function show(Company $company)
    {
        return view('admin.companies.show', compact('company'));
    }

    public function toggleStatus(Company $company)
    {
        $company->is_active = !$company->is_active;
        $company->save();

        $status = $company->is_active ? 'attivata' : 'disattivata';
        return back()->with('success', "Azienda {$status} con successo.");
    }
}
