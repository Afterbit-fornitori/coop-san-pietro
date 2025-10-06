<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyInvitation;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        // Verifica se c'è un token di invito
        $invitationToken = $request->query('invitation_token');
        $invitation = null;

        if ($invitationToken) {
            $invitation = CompanyInvitation::where('token', $invitationToken)
                ->where('status', 'pending')
                ->where('expires_at', '>', now())
                ->first();
        }

        return view('auth.register', compact('invitation'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'invitation_token' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();

        try {
            // Verifica se c'è un invito
            $invitation = null;
            if ($request->invitation_token) {
                $invitation = CompanyInvitation::where('token', $request->invitation_token)
                    ->where('status', 'pending')
                    ->where('expires_at', '>', now())
                    ->first();

                if (!$invitation) {
                    return back()->withErrors(['invitation_token' => 'Invito non valido o scaduto.']);
                }

                // Verifica che l'email corrisponda
                if ($invitation->email !== $request->email) {
                    return back()->withErrors(['email' => 'L\'email non corrisponde all\'invito.']);
                }
            }

            // Se c'è un invito, crea l'azienda e l'utente
            if ($invitation) {
                // Crea l'azienda invitata
                $company = Company::create([
                    'name' => $invitation->company_name,
                    'type' => 'invited',
                    'parent_company_id' => $invitation->inviter_company_id,
                    'domain' => \Str::slug($invitation->company_name) . '.local',
                    'is_active' => true,
                ]);

                // Crea l'utente admin per la nuova azienda
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'company_id' => $company->id,
                    'is_active' => true,
                ]);

                // Assegna ruolo COMPANY_ADMIN
                $user->assignRole('COMPANY_ADMIN');

                // Aggiorna lo status dell'invito
                $invitation->update(['status' => 'accepted']);

            } else {
                // Registrazione normale (senza invito) - probabilmente non dovrebbe accadere
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
            }

            DB::commit();

            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Errore durante la registrazione: ' . $e->getMessage()]);
        }
    }
}
