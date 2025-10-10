<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Company;
use App\Models\CompanyInvitation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use App\Mail\CompanyInvitationMail;
use App\Mail\CompanyAdminCreatedMail;

class CompanyController extends Controller
{
    public function __construct()
    {
        // Solo SUPER_ADMIN e COMPANY_ADMIN possono accedere al controllo aziende
        $this->middleware(['auth', 'role:SUPER_ADMIN|COMPANY_ADMIN']);
        $this->authorizeResource(Company::class, 'company');
    }

    /**
     * Helper per ottenere il prefisso della route in base al ruolo
     */
    private function getRoutePrefix(): string
    {
        return auth()->user()->hasRole('SUPER_ADMIN') ? 'admin' : 'company';
    }

    public function index()
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->hasRole('SUPER_ADMIN')) {
            // SUPER_ADMIN vede tutte le company
            $companies = Company::withCount('users')
                ->with(['parentCompany', 'childCompanies'])
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            // San Pietro (PROPRIETARIO della piattaforma) vede TUTTE le aziende
            // Può crearle, modificarle e gestirle tutte, anche quelle con parent diversi
            $companies = Company::withCount('users')
                ->with(['parentCompany', 'childCompanies'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Altri COMPANY_ADMIN vedono solo la propria azienda + sub-aziende assegnate
            $accessibleCompanyIds = $user->getAccessibleCompanies()->pluck('id');
            $companies = Company::withCount('users')
                ->with(['parentCompany', 'childCompanies'])
                ->whereIn('id', $accessibleCompanyIds)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        /** @var User $user */
        $user = auth()->user();
        $parentCompanies = [];
        $allowedTypes = [];

        if ($user->hasRole('SUPER_ADMIN')) {
            // SUPER_ADMIN può vedere tutte le aziende come possibili parent
            $parentCompanies = Company::orderBy('name')->get();
            $allowedTypes = ['main', 'invited'];
        } elseif ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            // San Pietro (PROPRIETARIO) può creare qualsiasi tipo di azienda
            // Vede tutte le aziende esistenti come possibili parent
            $parentCompanies = Company::orderBy('name')->get();
            $allowedTypes = ['main', 'invited'];
        } elseif ($user->hasRole('COMPANY_ADMIN')) {
            // Altri COMPANY_ADMIN possono creare solo aziende figlie (invited)
            // Vedono solo la propria azienda come parent disponibile
            $parentCompanies = Company::where('id', $user->company_id)->get();
            $allowedTypes = ['invited'];
        }

        return view('admin.companies.create', compact('parentCompanies', 'allowedTypes'));
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = auth()->user();

        // Definisci i tipi di azienda consentiti in base al ruolo
        $allowedTypes = [];
        if ($user->hasRole('SUPER_ADMIN')) {
            // SUPER_ADMIN può creare tutti i tipi di aziende
            $allowedTypes = ['main', 'invited'];
        } elseif ($user->hasRole('COMPANY_ADMIN')) {
            // COMPANY_ADMIN può creare aziende
            if ($user->company?->isSanPietro()) {
                // San Pietro (PROPRIETARIO) può creare tutti i tipi di aziende
                $allowedTypes = ['main', 'invited'];
            } else {
                // Altri COMPANY_ADMIN possono creare solo invited
                $allowedTypes = ['invited'];
            }
        } else {
            abort(403, 'Non hai i permessi per creare aziende');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|unique:companies,domain',
            'type' => 'required|string|in:' . implode(',', $allowedTypes),
            'parent_company_id' => 'nullable|exists:companies,id',
            'business_type' => 'nullable|string|max:100',
            'sector' => 'nullable|string|max:100',
            'vat_number' => 'nullable|string|max:11|unique:companies',
            'tax_code' => 'nullable|string|max:16|unique:companies',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'pec' => 'nullable|email|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            // Logica di autorizzazione per la creazione
            if (!$user->hasRole('SUPER_ADMIN')) {
                if ($user->hasRole('COMPANY_ADMIN')) {
                    // San Pietro (PROPRIETARIO) può creare qualsiasi tipo senza restrizioni
                    if ($user->company?->isSanPietro()) {
                        // San Pietro può creare aziende main o invited con qualsiasi parent
                        // Nessuna restrizione
                    } else {
                        // Altri COMPANY_ADMIN possono creare solo invited come figlie
                        if ($validated['type'] !== 'invited') {
                            throw new \Exception('Puoi creare solo aziende di tipo Invited');
                        }

                        // Devono usare la propria company come parent
                        if (isset($validated['parent_company_id']) && $validated['parent_company_id'] !== $user->company_id) {
                            throw new \Exception('Puoi creare aziende solo come figlie della tua azienda');
                        }
                        $validated['parent_company_id'] = $user->company_id;
                    }
                }
            }

            // Crea l'azienda
            $company = Company::create([
                'name' => $validated['name'],
                'domain' => $validated['domain'],
                'type' => $validated['type'],
                'parent_company_id' => $validated['parent_company_id'] ?? null,
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

            // Crea automaticamente un admin per la nuova azienda
            $adminCreated = false;
            $adminEmailSent = false;
            if (!empty($validated['email'])) {
                // Genera una password temporanea
                $temporaryPassword = Str::random(12);

                $admin = User::create([
                    'name' => $validated['name'] . ' Admin',
                    'email' => $validated['email'],
                    'password' => Hash::make($temporaryPassword),
                    'company_id' => $company->id,
                    'is_active' => true,
                    'email_verified_at' => null, // Richiede verifica email
                ]);

                // Assegna il ruolo COMPANY_ADMIN
                $admin->assignRole('COMPANY_ADMIN');
                $adminCreated = true;

                // Log della password temporanea (backup in caso email fallisca)
                Log::info("Admin creato per azienda {$company->name} - Email: {$validated['email']} - Password temporanea: {$temporaryPassword}");

                // Invia email con credenziali
                try {
                    Mail::to($validated['email'])->send(new CompanyAdminCreatedMail($company, $admin, $temporaryPassword));
                    $adminEmailSent = true;
                    Log::info('Email credenziali inviata con successo a: ' . $validated['email']);

                    // Invia email di verifica
                    $admin->sendEmailVerificationNotification();
                    Log::info('Email verifica inviata con successo a: ' . $validated['email']);
                } catch (\Exception $mailError) {
                    Log::error('ERRORE INVIO EMAIL: ' . $mailError->getMessage());
                    Log::error('Stack trace: ' . $mailError->getTraceAsString());
                }
            }

            // Se l'azienda è di tipo "invited" e ha un'email, crea automaticamente l'invito per tracciamento
            if ($validated['type'] === 'invited' && !empty($validated['email'])) {
                // Verifica se esiste già un invito per questa email
                $existingInvitation = CompanyInvitation::where('email', $validated['email'])
                    ->whereIn('status', ['pending', 'viewed'])
                    ->first();

                if (!$existingInvitation) {
                    $invitation = CompanyInvitation::create([
                        'inviter_company_id' => $user->company_id, // L'azienda che crea l'invito (San Pietro)
                        'invited_company_id' => $company->id, // ID dell'azienda appena creata
                        'company_name' => $validated['name'],
                        'email' => $validated['email'],
                        'business_type' => $validated['business_type'] ?? null,
                        'sector' => $validated['sector'] ?? null,
                        'permissions' => ['members', 'productions', 'documents', 'reports'], // Tutti i permessi
                        'token' => Str::random(64),
                        'status' => 'pending', // PENDING: cambierà ad 'accepted' al primo login
                        'expires_at' => now()->addDays(30),
                    ]);

                    Log::info("Invito tracciamento creato per azienda {$company->name} - Status: pending (cambierà ad accepted al login)");
                }
            }

            DB::commit();

            $message = "Azienda '{$company->name}' creata con successo.";
            if ($adminCreated) {
                $message .= " Admin creato con email: {$validated['email']}.";
                if ($adminEmailSent) {
                    $message .= " Email con credenziali e link verifica inviati.";
                } else {
                    $message .= " ⚠️ Email credenziali non inviata (controlla log).";
                }
            }

            return redirect()->route($this->getRoutePrefix() . '.companies.index')
                ->with('success', $message);
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
        $allowedTypes = [];

        if ($user->hasRole('SUPER_ADMIN')) {
            // SUPER_ADMIN vede tutte le aziende tranne quella corrente
            $parentCompanies = Company::where('id', '!=', $company->id)
                ->orderBy('name')
                ->get();
            $allowedTypes = ['main', 'invited'];
        } elseif ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            // San Pietro vede tutte le aziende tranne quella corrente
            $parentCompanies = Company::where('id', '!=', $company->id)
                ->orderBy('name')
                ->get();
            $allowedTypes = ['main', 'invited'];
        } elseif ($user->hasRole('COMPANY_ADMIN')) {
            // Altri COMPANY_ADMIN vedono solo aziende accessibili come parent
            $accessibleCompanyIds = $user->getAccessibleCompanies()->pluck('id');
            $parentCompanies = Company::whereIn('id', $accessibleCompanyIds)
                ->where('id', '!=', $company->id)
                ->orderBy('name')
                ->get();
            $allowedTypes = ['invited'];
        }

        return view('admin.companies.edit', compact('company', 'parentCompanies', 'allowedTypes'));
    }

    public function update(Request $request, Company $company)
    {
        /** @var User $user */
        $user = $request->user();

        // Definisci i tipi consentiti in base al ruolo
        $allowedTypes = [];
        if ($user->hasRole('SUPER_ADMIN')) {
            $allowedTypes = ['main', 'invited'];
        } elseif ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            // San Pietro può modificare qualsiasi tipo
            $allowedTypes = ['main', 'invited'];
        } else {
            $allowedTypes = ['invited'];
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|unique:companies,domain,' . $company->id,
            'type' => 'required|string|in:' . implode(',', $allowedTypes),
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
            'is_active' => 'nullable|boolean'
        ]);

        // Assicura che is_active sia booleano
        $validated['is_active'] = $request->has('is_active');

        $company->update($validated);

        return redirect()->route($this->getRoutePrefix() . '.companies.index')
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

        return redirect()->route($this->getRoutePrefix() . '.companies.index')
            ->with('success', "Azienda '{$companyName}' eliminata con successo.");
    }

    public function show(Company $company)
    {
        // Carica solo le relazioni che non hanno problemi con il tenant scope
        $company->load(['users', 'parentCompany', 'childCompanies']);

        // Per SUPER_ADMIN, carica anche members e productions senza applicare scope
        $user = auth()->user();
        if ($user->hasRole('SUPER_ADMIN')) {
            $company->load([
                'members' => function ($query) {
                    $query->withoutGlobalScopes();
                },
                'productions' => function ($query) {
                    $query->withoutGlobalScopes();
                }
            ]);
        }

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
