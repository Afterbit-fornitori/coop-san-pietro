<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Models\User;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;
use Illuminate\Http\Request;

class UserBasedTenantFinder extends TenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        // Se l'utente non è autenticato, non c'è tenant
        if (!auth()->check()) {
            return null;
        }

        /** @var User $user */
        $user = auth()->user();

        // SUPER_ADMIN non ha tenant specifico - può accedere a tutto
        if ($user->hasRole('SUPER_ADMIN')) {
            // Per SUPER_ADMIN, il tenant può essere determinato da parametri della request
            // Se c'è un company_id nella route, usa quello come tenant
            if ($request->route('company')) {
                return $request->route('company');
            }
            
            // Altrimenti nessun tenant specifico (accesso globale)
            return null;
        }

        // Per tutti gli altri utenti, il tenant è la loro company
        if ($user->company_id) {
            return Company::find($user->company_id);
        }

        // Se l'utente non ha company_id, non può accedere
        return null;
    }
}