<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::all();
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('companies.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:main,master,invited',
            'parent_company_id' => 'nullable|exists:companies,id',
            'domain' => 'required|string|unique:companies,domain',
            'vat_number' => 'nullable|string|max:20',
            'tax_code' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:2',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pec' => 'nullable|email|max:255'
        ]);

        // Se il tipo è main, rimuovi il parent_company_id
        if ($validated['type'] === 'main') {
            $validated['parent_company_id'] = null;
        }

        // Se il tipo è invited, assicurati che ci sia un parent_company_id
        if ($validated['type'] === 'invited' && empty($validated['parent_company_id'])) {
            return back()
                ->withErrors(['parent_company_id' => 'Per le aziende di tipo invited è necessario specificare un\'azienda principale'])
                ->withInput();
        }

        $company = Company::create($validated);

        return redirect()->route('companies.index')
            ->with('success', 'Azienda creata con successo');
    }

    public function edit(Company $company)
    {
        $companies = Company::where('id', '!=', $company->id)->get();
        return view('companies.edit', compact('company', 'companies'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:main,master,invited',
            'parent_company_id' => 'nullable|exists:companies,id',
            'domain' => 'required|string|unique:companies,domain,' . $company->id,
            'vat_number' => 'nullable|string|max:20',
            'tax_code' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:2',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pec' => 'nullable|email|max:255'
        ]);

        // Se il tipo è main, rimuovi il parent_company_id
        if ($validated['type'] === 'main') {
            $validated['parent_company_id'] = null;
        }

        // Se il tipo è invited, assicurati che ci sia un parent_company_id
        if ($validated['type'] === 'invited' && empty($validated['parent_company_id'])) {
            return back()
                ->withErrors(['parent_company_id' => 'Per le aziende di tipo invited è necessario specificare un\'azienda principale'])
                ->withInput();
        }

        $company->update($validated);

        return redirect()->route('companies.index')
            ->with('success', 'Azienda aggiornata con successo');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')
            ->with('success', 'Azienda eliminata con successo');
    }
}
