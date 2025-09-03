<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class CompanyController extends Controller
{
    public function __construct()
    {
        // Solo SUPER_ADMIN e COMPANY_ADMIN possono accedere al controllo aziende
        $this->middleware(['auth', 'role:SUPER_ADMIN|COMPANY_ADMIN']);
    }

    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->hasRole('SUPER_ADMIN')) {
            // SUPER_ADMIN vede tutte le company
            $companies = Company::with(['users', 'parentCompany', 'childCompanies'])->get();
        } elseif ($user->hasRole('COMPANY_ADMIN') && $user->company && $user->company->isMain()) {
            // San Pietro (main company) vede la propria azienda e quelle invitate
            $companies = Company::with(['users', 'parentCompany', 'childCompanies'])
                ->where(function ($q) use ($user) {
                    $q->where('id', $user->company_id)
                        ->orWhere('parent_company_id', $user->company_id);
                })->get();
        } else {
            // Altri COMPANY_ADMIN vedono solo la propria azienda
            $companies = Company::with(['users', 'parentCompany', 'childCompanies'])
                ->where('id', $user->company_id)->get();
        }

        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        // Ottieni le aziende che possono essere parent (solo per SUPER_ADMIN)
        /** @var User $user */
        $user = auth()->user();
        $parentCompanies = [];
        if ($user->hasRole('SUPER_ADMIN')) {
            $parentCompanies = Company::whereIn('type', ['master', 'main'])->get();
        } elseif ($user->hasRole('COMPANY_ADMIN')) {
            // COMPANY_ADMIN può creare solo aziende figlie della propria
            $parentCompanies = Company::where('id', $user->company_id)->get();
        }

        return view('admin.companies.create', compact('parentCompanies'));
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:master,main,invited',
            'parent_company_id' => 'nullable|exists:companies,id',
            'vat_number' => 'nullable|string|max:11|unique:companies',
            'tax_code' => 'nullable|string|max:16|unique:companies',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pec' => 'nullable|email|max:255',
            'is_active' => 'boolean',

            // Dati per il primo utente amministratore della company
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|string|email|max:255|unique:users,email',
            'admin_password' => ['required', Rules\Password::defaults()],
            'admin_role' => 'required|string|in:COMPANY_ADMIN,COMPANY_USER'
        ]);

        DB::beginTransaction();

        try {
            // Logica di autorizzazione per la creazione
            if ($user->hasRole('COMPANY_ADMIN') && $validated['type'] !== 'invited') {
                throw new \Exception('COMPANY_ADMIN può creare solo aziende invitate');
            }

            if ($user->hasRole('COMPANY_ADMIN') && $validated['parent_company_id'] !== $user->company_id) {
                throw new \Exception('COMPANY_ADMIN può creare aziende solo come figlie della propria');
            }

            // Crea l'azienda
            $company = Company::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'parent_company_id' => $validated['parent_company_id'],
                'vat_number' => $validated['vat_number'],
                'tax_code' => $validated['tax_code'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'province' => $validated['province'],
                'zip_code' => $validated['zip_code'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'pec' => $validated['pec'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // Crea automaticamente l'utente amministratore per l'azienda
            $adminUser = User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'company_id' => $company->id,
                'is_active' => true,
                'email_verified_at' => now(), // Auto-verifica per admin creati dal sistema
            ]);

            // Assegna il ruolo
            $adminUser->assignRole($validated['admin_role']);

            DB::commit();

            return redirect()->route('admin.companies.index')
                ->with('success', "Azienda '{$company->name}' e utente amministratore creati con successo.");
        } catch (\Exception $e) {
            DB::rollback();

            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(Company $company)
    {
        /** @var User $user */
        $user = auth()->user();
        
        $parentCompanies = [];
        if ($user->hasRole('SUPER_ADMIN')) {
            $parentCompanies = Company::whereIn('type', ['master', 'main'])
                ->where('id', '!=', $company->id) // Evita auto-riferimenti
                ->get();
        }

        return view('admin.companies.edit', compact('company', 'parentCompanies'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:master,main,invited',
            'parent_company_id' => 'nullable|exists:companies,id',
            'vat_number' => 'nullable|string|max:11|unique:companies,vat_number,' . $company->id,
            'tax_code' => 'nullable|string|max:16|unique:companies,tax_code,' . $company->id,
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pec' => 'nullable|email|max:255',
            'is_active' => 'boolean'
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Azienda aggiornata con successo.');
    }

    public function destroy(Company $company)
    {
        // Solo SUPER_ADMIN può eliminare aziende
        /** @var User $user */
        $user = auth()->user();
        if (!$user->hasRole('SUPER_ADMIN')) {
            abort(403, 'Solo il Super Admin può eliminare aziende.');
        }

        // Controlla se ci sono utenti o dati collegati
        if ($company->users()->count() > 0) {
            return back()->withErrors(['error' => 'Impossibile eliminare: azienda con utenti associati.']);
        }

        if ($company->members()->count() > 0) {
            return back()->withErrors(['error' => 'Impossibile eliminare: azienda con membri associati.']);
        }

        $companyName = $company->name;
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', "Azienda '{$companyName}' eliminata con successo.");
    }

    public function show(Company $company)
    {
        $company->load(['users', 'parentCompany', 'childCompanies', 'members', 'productions']);

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
