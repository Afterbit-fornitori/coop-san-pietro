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

    public function index(Request $request)
    {
        $query = User::query()->with(['company', 'roles']);

        // Filtra gli utenti in base al ruolo dell'utente corrente
        if ($request->user()->hasRole('company-admin')) {
            // Admin della company vede solo gli utenti della sua company e delle company figlie
            $query->whereIn('company_id', [
                $request->user()->company_id,
                ...Company::where('parent_id', $request->user()->company_id)->pluck('id')
            ]);
        } elseif (!$request->user()->hasRole('super-admin')) {
            // Utenti normali vedono solo il proprio profilo
            $query->where('id', $request->user()->id);
        }

        $users = $query->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $companies = Company::all();
        $roles = Role::all();
        return view('admin.users.create', compact('companies', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'company_id' => 'required|exists:companies,id',
            'role' => 'required|exists:roles,name'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'company_id' => $validated['company_id']
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utente creato con successo.');
    }

    public function edit(User $user)
    {
        $companies = Company::all();
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'companies', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'company_id' => 'required|exists:companies,id',
            'role' => 'required|exists:roles,name'
        ]);

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

        return redirect()->route('admin.users.index')
            ->with('success', 'Utente aggiornato con successo.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utente eliminato con successo.');
    }
}
