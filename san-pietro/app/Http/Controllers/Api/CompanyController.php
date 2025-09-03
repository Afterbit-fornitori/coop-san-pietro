<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Company::class, 'company');
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Company::query()
            ->withCount('users')
            ->with(['parentCompany', 'childCompanies']);

        if ($request->user()->hasRole('company_admin')) {
            $query->where('id', $request->user()->company_id)
                  ->orWhere('parent_id', $request->user()->company_id);
        } elseif (!$request->user()->hasRole('super_admin')) {
            $query->where('id', $request->user()->company_id);
        }

        $companies = $query->paginate($request->input('per_page', 15));

        return CompanyResource::collection($companies);
    }

    public function store(CompanyRequest $request): CompanyResource
    {
        $company = Company::create($request->validated());

        return new CompanyResource($company);
    }

    public function show(Company $company): CompanyResource
    {
        $company->load(['parentCompany', 'childCompanies'])
               ->loadCount('users');

        return new CompanyResource($company);
    }

    public function update(CompanyRequest $request, Company $company): CompanyResource
    {
        $company->update($request->validated());

        return new CompanyResource($company);
    }

    public function destroy(Company $company): Response
    {
        $company->delete();

        return response()->noContent();
    }

    public function invite(Request $request, Company $company)
    {
        $this->authorize('invite', $company);

        $request->validate([
            'email' => ['required', 'email'],
            'name' => ['required', 'string'],
            'role' => ['required', 'string', 'in:company_admin,company_user']
        ]);

        // TODO: Implementare la logica di invito
        // Per ora restituiamo un placeholder
        return response()->json([
            'message' => 'Invitation sent successfully',
            'status' => 'pending'
        ]);
    }
}
