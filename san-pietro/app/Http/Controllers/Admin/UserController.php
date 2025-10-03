<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Helper per ottenere il prefisso della route in base al ruolo
     */
    private function getRoutePrefix(): string
    {
        return auth()->user()->hasRole('SUPER_ADMIN') ? 'admin' : 'company';
    }

    public function index(Request $request)
    {
        $query = User::query()->with(['company', 'roles']);

        // Filtra gli utenti in base al ruolo dell'utente corrente
        /** @var User $currentUser */
        $currentUser = $request->user();
        if ($currentUser->hasRole('COMPANY_ADMIN')) {
            // Admin della company vede solo gli utenti della sua company e delle company figlie
            $query->whereIn('company_id', [
                $currentUser->company_id,
                ...Company::where('parent_company_id', $currentUser->company_id)->pluck('id')
            ]);
        } elseif (!$currentUser->hasRole('SUPER_ADMIN')) {
            // Utenti normali vedono solo il proprio profilo
            $query->where('id', $currentUser->id);
        }

        $users = $query->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        /** @var User $currentUser */
        $currentUser = auth()->user();

        // Determina le aziende accessibili
        if ($currentUser->hasRole('SUPER_ADMIN')) {
            $companies = Company::all();
        } elseif ($currentUser->hasRole('COMPANY_ADMIN') && $currentUser->company) {
            // COMPANY_ADMIN vede solo la propria azienda e quelle invitate
            $companies = $currentUser->getAccessibleCompanies();
        } else {
            $companies = collect([]);
        }

        // Determina i ruoli assegnabili in base al ruolo dell'utente corrente
        if ($currentUser->hasRole('SUPER_ADMIN')) {
            // SUPER_ADMIN può assegnare qualsiasi ruolo
            $roles = Role::all();
        } elseif ($currentUser->hasRole('COMPANY_ADMIN')) {
            // COMPANY_ADMIN può assegnare solo COMPANY_ADMIN e COMPANY_USER
            $roles = Role::whereIn('name', ['COMPANY_ADMIN', 'COMPANY_USER'])->get();
        } else {
            // Altri ruoli non possono creare utenti
            $roles = collect([]);
        }

        return view('admin.users.create', compact('companies', 'roles'));
    }

    public function store(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'company_id' => 'required|exists:companies,id',
            'role' => 'required|exists:roles,name'
        ]);

        // Verifica che l'utente possa creare utenti per questa azienda
        if (!$currentUser->hasRole('SUPER_ADMIN') && !$currentUser->hasRole('COMPANY_ADMIN')) {
            if (!$currentUser->canAccessCompany($validated['company_id'])) {
                abort(403, 'Non hai i permessi per creare utenti in questa azienda.');
            }
        }

        // Verifica che COMPANY_ADMIN non possa assegnare SUPER_ADMIN
        if ($currentUser->hasRole('COMPANY_ADMIN') && $validated['role'] === 'SUPER_ADMIN') {
            abort(403, 'Non hai i permessi per assegnare il ruolo di Super Admin.');
        }

        // Verifica che COMPANY_ADMIN possa assegnare solo COMPANY_ADMIN e COMPANY_USER
        if ($currentUser->hasRole('COMPANY_ADMIN') && !in_array($validated['role'], ['COMPANY_ADMIN', 'COMPANY_USER'])) {
            abort(403, 'Puoi assegnare solo i ruoli Company Admin o Company User.');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'company_id' => $validated['company_id'],
            'is_active' => true
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route($this->getRoutePrefix() . '.users.index')
            ->with('success', 'Utente creato con successo.');
    }

    public function edit(User $user)
    {
        /** @var User $currentUser */
        $currentUser = auth()->user();

        // Determina le aziende accessibili
        if ($currentUser->hasRole('SUPER_ADMIN')) {
            $companies = Company::all();
        } elseif ($currentUser->hasRole('COMPANY_ADMIN') && $currentUser->company) {
            // COMPANY_ADMIN vede solo la propria azienda e quelle invitate
            $companies = $currentUser->getAccessibleCompanies();
        } else {
            $companies = collect([]);
        }

        // Determina i ruoli assegnabili in base al ruolo dell'utente corrente
        if ($currentUser->hasRole('SUPER_ADMIN')) {
            // SUPER_ADMIN può assegnare qualsiasi ruolo
            $roles = Role::all();
        } elseif ($currentUser->hasRole('COMPANY_ADMIN')) {
            // COMPANY_ADMIN può assegnare solo COMPANY_ADMIN e COMPANY_USER
            $roles = Role::whereIn('name', ['COMPANY_ADMIN', 'COMPANY_USER'])->get();
        } else {
            // Altri ruoli non possono modificare ruoli
            $roles = collect([]);
        }

        return view('admin.users.edit', compact('user', 'companies', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        /** @var User $currentUser */
        $currentUser = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'company_id' => 'required|exists:companies,id',
            'role' => 'required|exists:roles,name'
        ]);

        // Verifica che COMPANY_ADMIN non possa assegnare SUPER_ADMIN
        if ($currentUser->hasRole('COMPANY_ADMIN') && $validated['role'] === 'SUPER_ADMIN') {
            abort(403, 'Non hai i permessi per assegnare il ruolo di Super Admin.');
        }

        // Verifica che COMPANY_ADMIN possa assegnare solo COMPANY_ADMIN e COMPANY_USER
        if ($currentUser->hasRole('COMPANY_ADMIN') && !in_array($validated['role'], ['COMPANY_ADMIN', 'COMPANY_USER'])) {
            abort(403, 'Puoi assegnare solo i ruoli Company Admin o Company User.');
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'company_id' => $validated['company_id']
        ];

        if (!empty($validated['password'])) {
            $data['password'] = bcrypt($validated['password']);
        }

        $user->update($data);

        // Aggiorna il ruolo solo se è cambiato
        if (!$user->hasRole($validated['role'])) {
            $user->roles()->detach();
            $user->assignRole($validated['role']);
        }

        return redirect()->route($this->getRoutePrefix() . '.users.index')
            ->with('success', 'Utente aggiornato con successo.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route($this->getRoutePrefix() . '.users.index')
            ->with('success', 'Utente eliminato con successo.');
    }

    public function toggleStatus(User $user)
    {
        $this->authorize('update', $user);

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'attivato' : 'disattivato';
        return redirect()->back()->with('success', "L'utente è stato {$status} con successo.");
    }
}
