<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckCompanyAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = User::find(Auth::id());

        // Se è un super-admin, può accedere a tutto
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // Se è l'admin di San Pietro, può gestire le aziende
        if ($user->hasRole('company-admin') && $user->company->domain === 'san-pietro.test') {
            return $next($request);
        }

        // Per gli altri admin, possono gestire solo i propri utenti
        if ($user->hasRole('company-admin')) {
            // Se stanno gestendo utenti
            if (strpos($request->route()->getName(), 'admin.users.') === 0) {
                $requestedUser = $request->route('user');
                if ($requestedUser && $requestedUser->company_id !== $user->company_id) {
                    abort(403, 'Non puoi gestire utenti di altre aziende');
                }
                return $next($request);
            }
            
            // Se stanno gestendo la propria azienda
            $requestedCompany = $request->route('company');
            if ($requestedCompany && $requestedCompany->id === $user->company_id) {
                return $next($request);
            }
        }

        abort(403, 'Non hai i permessi necessari');
    }
}
