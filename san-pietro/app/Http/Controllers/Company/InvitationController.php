<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            /** @var User $user */
            $user = auth()->user();
            
            // Solo San Pietro (main company) può invitare altre aziende
            if (!$user->hasRole('COMPANY_ADMIN') || !$user->company->isMain()) {
                abort(403, 'Solo San Pietro può inviare inviti ad altre aziende.');
            }
            
            return $next($request);
        });
    }

    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        
        $invitations = CompanyInvitation::where('inviter_company_id', $user->company_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('company.invitations.index', compact('invitations'));
    }

    public function create()
    {
        return view('company.invitations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:company_invitations,email',
            'company_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'sector' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'string|in:members,productions,documents,reports'
        ]);

        /** @var User $user */
        $user = auth()->user();

        $invitation = CompanyInvitation::create([
            'inviter_company_id' => $user->company_id,
            'email' => $validated['email'],
            'company_name' => $validated['company_name'],
            'business_type' => $validated['business_type'],
            'sector' => $validated['sector'],
            'permissions' => $validated['permissions'] ?? [],
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7), // Invito valido per 7 giorni
            'status' => 'pending'
        ]);

        // TODO: Inviare email di invito
        // Mail::to($validated['email'])->send(new CompanyInvitationMail($invitation));

        return redirect()->route('invitations.index')
            ->with('success', "Invito inviato a {$validated['company_name']} ({$validated['email']}).");
    }

    public function show(CompanyInvitation $invitation)
    {
        return view('company.invitations.show', compact('invitation'));
    }

    public function resend(CompanyInvitation $invitation)
    {
        if ($invitation->status !== 'pending') {
            return back()->withErrors(['error' => 'Solo gli inviti in attesa possono essere reinviati.']);
        }

        // Aggiorna la data di scadenza
        $invitation->update([
            'expires_at' => now()->addDays(7),
            'token' => Str::random(64)
        ]);

        // TODO: Inviare email di invito
        // Mail::to($invitation->email)->send(new CompanyInvitationMail($invitation));

        return back()->with('success', 'Invito reinviato con successo.');
    }

    public function destroy(CompanyInvitation $invitation)
    {
        $companyName = $invitation->company_name;
        $invitation->delete();

        return redirect()->route('invitations.index')
            ->with('success', "Invito per {$companyName} eliminato con successo.");
    }
}