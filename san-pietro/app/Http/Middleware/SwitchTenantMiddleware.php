<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;
use App\Models\Company;
use App\Models\User;

class SwitchTenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        }

        /** @var User $user */
        $user = auth()->user();

        // SUPER_ADMIN - tenant context dinamico basato sulla route
        if ($user->hasRole('SUPER_ADMIN')) {
            $tenant = $this->getTenantFromRoute($request);
            if ($tenant) {
                $tenant->makeCurrent();
            }
            // Se non c'è tenant specifico, SUPER_ADMIN opera in modalità globale
            return $next($request);
        }

        // Per tutti gli altri utenti, imposta il tenant basato sulla loro company
        if ($user->company_id) {
            $tenant = Company::find($user->company_id);
            if ($tenant) {
                $tenant->makeCurrent();
            }
        }

        return $next($request);
    }

    private function getTenantFromRoute(Request $request): ?Tenant
    {
        // Controlla se c'è un parametro company nella route
        if ($request->route('company')) {
            return $request->route('company');
        }

        // Controlla se c'è un parametro user nella route e prende la sua company
        if ($request->route('user')) {
            $user = $request->route('user');
            if ($user->company_id) {
                return Company::find($user->company_id);
            }
        }

        // Controlla se c'è un parametro member nella route e prende la sua company
        if ($request->route('member')) {
            $member = $request->route('member');
            if ($member->company_id) {
                return Company::find($member->company_id);
            }
        }

        return null;
    }
}