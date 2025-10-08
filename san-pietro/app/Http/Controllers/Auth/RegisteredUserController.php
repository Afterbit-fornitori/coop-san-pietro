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
        // SICUREZZA: La registrazione è possibile SOLO tramite invito
        $invitationToken = $request->query('invitation_token');

        if (!$invitationToken) {
            abort(403, 'La registrazione è possibile solo tramite invito. Contattare l\'amministratore.');
        }

        $invitation = CompanyInvitation::where('token', $invitationToken)
            ->whereIn('status', ['pending', 'viewed'])
            ->where('expires_at', '>', now())
            ->first();

        if (!$invitation) {
            abort(403, 'Invito non valido o scaduto.');
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
            'invitation_token' => ['required', 'string'], // SICUREZZA: Ora obbligatorio
        ]);

        DB::beginTransaction();

        try {
            // SICUREZZA: L'invito è OBBLIGATORIO per la registrazione
            $invitation = CompanyInvitation::where('token', $request->invitation_token)
                ->whereIn('status', ['pending', 'viewed'])
                ->where('expires_at', '>', now())
                ->first();

            if (!$invitation) {
                DB::rollBack();
                return back()->withErrors(['invitation_token' => 'Invito non valido o scaduto.']);
            }

            // Verifica che l'email corrisponda
            if ($invitation->email !== $request->email) {
                DB::rollBack();
                return back()->withErrors(['email' => 'L\'email non corrisponde all\'invito.']);
            }

            // Gestisci l'azienda e l'utente tramite invito
            // Verifica se l'azienda esiste già (creata da San Pietro)
            if ($invitation->invited_company_id) {
                // L'azienda esiste già - usa quella
                $company = Company::findOrFail($invitation->invited_company_id);

                // Verifica se esiste già un admin con questa email
                $existingUser = User::where('email', $request->email)
                    ->where('company_id', $company->id)
                    ->first();

                if ($existingUser) {
                    // L'utente esiste già - aggiorna solo la password con quella scelta dall'utente
                    $existingUser->update([
                        'name' => $request->name,
                        'password' => Hash::make($request->password),
                        'is_active' => true,
                    ]);
                    $user = $existingUser;
                } else {
                    // Crea nuovo utente admin
                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'company_id' => $company->id,
                        'is_active' => true,
                    ]);

                    // Assegna ruolo COMPANY_ADMIN
                    $user->assignRole('COMPANY_ADMIN');
                }
            } else {
                // L'azienda NON esiste - creala ora
                $company = Company::create([
                    'name' => $invitation->company_name,
                    'type' => 'invited',
                    'parent_company_id' => $invitation->inviter_company_id,
                    'domain' => \Str::slug($invitation->company_name) . '.local',
                    'email' => $request->email,
                    'is_active' => true,
                ]);

                // Aggiorna l'invito con l'ID dell'azienda appena creata
                $invitation->update(['invited_company_id' => $company->id]);

                // Crea l'utente admin per l'azienda
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'company_id' => $company->id,
                    'is_active' => true,
                ]);

                // Assegna ruolo COMPANY_ADMIN
                $user->assignRole('COMPANY_ADMIN');
            }

            // Aggiorna lo status dell'invito ad "accepted"
            $invitation->update(['status' => 'accepted']);

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
