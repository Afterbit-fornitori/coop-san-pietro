<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Mail\CompanyAdminCreatedMail;
use App\Models\CompanyInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            /** @var User $user */
            $user = Auth::user();

            // SUPER_ADMIN: ha sempre accesso
            if ($user->hasRole('SUPER_ADMIN')) {
                return $next($request);
            }

            // San Pietro (COMPANY_ADMIN della piattaforma proprietaria): ha accesso
            if ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
                return $next($request);
            }

            // Tutti gli altri: accesso negato
            abort(403, 'Solo il SUPER_ADMIN o San Pietro possono gestire gli inviti.');
        });
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // San Pietro (proprietario piattaforma) vede TUTTI gli inviti
        // Gli inviti vengono creati automaticamente quando si crea un'azienda in Admin\CompanyController
        if ($user->hasRole('SUPER_ADMIN') || $user->company?->isSanPietro()) {
            $invitations = CompanyInvitation::with('invitedCompany')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $invitations = CompanyInvitation::with('invitedCompany')
                ->where('inviter_company_id', $user->company_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('company.invitations.index', compact('invitations'));
    }

    public function show(CompanyInvitation $invitation)
    {
        $this->authorize('view', $invitation);
        return view('company.invitations.show', compact('invitation'));
    }

    public function resend(CompanyInvitation $invitation)
    {
        $this->authorize('update', $invitation);

        // Trova l'utente admin dell'azienda invitata
        $admin = User::where('company_id', $invitation->invited_company_id)
            ->whereHas('roles', function ($q) {
                $q->where('name', 'COMPANY_ADMIN');
            })
            ->first();

        if (!$admin) {
            return back()->withErrors(['error' => 'Admin dell\'azienda non trovato.']);
        }

        // Genera nuova password temporanea
        $temporaryPassword = Str::random(12);
        $admin->update(['password' => Hash::make($temporaryPassword)]);

        // Invia email con nuove credenziali
        Mail::to($admin->email)->send(new CompanyAdminCreatedMail(
            $admin->company,
            $admin,
            $temporaryPassword
        ));

        Log::info("Credenziali reinviate per {$admin->email} - Nuova password temporanea: {$temporaryPassword}");

        return back()->with('success', 'Credenziali reinviate con successo all\'admin.');
    }

    public function destroy(CompanyInvitation $invitation)
    {
        $this->authorize('delete', $invitation);

        $companyName = $invitation->company_name;
        $invitation->delete();

        return redirect()->route('company.invitations.index')
            ->with('success', "Invito per {$companyName} eliminato con successo.");
    }

    public function accept(string $token)
    {
        $invitation = CompanyInvitation::where('token', $token)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if (!$invitation) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Invito non valido o scaduto.']);
        }

        // Aggiorna status a "viewed" quando l'invito viene visualizzato
        $invitation->update(['status' => 'viewed']);

        // Reindirizza alla pagina di registrazione con il token nell'URL
        return redirect()->route('register', ['invitation_token' => $token]);
    }
}
