<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Models\Company;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * Logica multi-tenant:
     * - SUPER_ADMIN: vede tutto (nessuno scope applicato)
     * - COMPANY_ADMIN (San Pietro - main): vede propria azienda + aziende invitate (child)
     * - COMPANY_ADMIN (altre) / COMPANY_USER: vede solo propria azienda
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Se non c'è utente autenticato, non applicare scope
        if (!auth()->check()) {
            return;
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();

        // SUPER_ADMIN: accesso globale, nessuno scope applicato
        if ($user->hasRole('SUPER_ADMIN')) {
            return;
        }

        // COMPANY_ADMIN di San Pietro (main): accede ai propri dati + aziende invitate
        if ($user->hasRole('COMPANY_ADMIN') && $user->company && $user->company->isMain()) {
            // Ottiene IDs delle aziende accessibili (propria + child companies)
            $accessibleCompanyIds = Company::where('id', $user->company_id)
                ->orWhere('parent_company_id', $user->company_id)
                ->pluck('id')
                ->toArray();

            $builder->whereIn($model->getTable() . '.company_id', $accessibleCompanyIds);
            return;
        }

        // COMPANY_ADMIN di altre aziende o COMPANY_USER: solo propria azienda
        if ($user->company_id) {
            $builder->where($model->getTable() . '.company_id', $user->company_id);
        }
    }
}