<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('super-admin') || ($user->hasRole('COMPANY_ADMIN') && $user->company->domain === 'san-pietro.test')) {
            // Super admin e admin di San Pietro vedono tutte le aziende
            $companies = Company::all();
        } else {
            // Gli altri admin vedono solo la propria azienda se è attiva
            $companies = Company::where('id', $user->company_id)
                ->where('is_active', true)
                ->get();
        }

        return response()->json(['data' => $companies]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|unique:companies',
            'type' => 'required|in:parent,child',
            'vat_number' => 'nullable|string|size:11|unique:companies',
            'tax_code' => 'nullable|string|size:16',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|size:2',
            'zip_code' => 'nullable|string|max:10',
            'is_active' => 'boolean',
            'settings' => 'nullable|array'
        ]);

        $company = Company::create($validated);

        return response()->json(['data' => $company], 201);
    }

    public function show(Company $company, Request $request)
    {
        $user = $request->user();

        if (
            !$user->hasRole('super-admin') &&
            !($user->hasRole('COMPANY_ADMIN') && $user->company->domain === 'san-pietro.test')
        ) {
            // Se non è super admin o admin di San Pietro, può vedere solo la propria azienda attiva
            if ($company->id !== $user->company_id || !$company->is_active) {
                abort(403);
            }
        }

        return response()->json(['data' => $company]);
    }

    public function update(Company $company, Request $request)
    {
        $user = $request->user();

        if (
            !$user->hasRole('super-admin') &&
            !($user->hasRole('COMPANY_ADMIN') && $user->company->domain === 'san-pietro.test')
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'domain' => 'sometimes|string|unique:companies,domain,' . $company->id,
            'type' => 'sometimes|in:parent,child',
            'vat_number' => 'nullable|string|size:11|unique:companies,vat_number,' . $company->id,
            'tax_code' => 'nullable|string|size:16',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|size:2',
            'zip_code' => 'nullable|string|max:10',
            'is_active' => 'boolean',
            'settings' => 'nullable|array'
        ]);

        $company->update($validated);

        return response()->json(['data' => $company]);
    }

    public function destroy(Company $company, Request $request)
    {
        $user = $request->user();

        if (!$user->hasRole('super-admin')) {
            abort(403);
        }

        $company->delete();

        return response()->noContent();
    }
}
