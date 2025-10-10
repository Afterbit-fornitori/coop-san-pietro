<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view companies');
    }

    public function view(User $user, Company $company): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // San Pietro (PROPRIETARIO) può vedere TUTTE le aziende
        if ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            return true;
        }

        // Altri Company admin possono vedere la propria company e le sue child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $user->company_id === $company->id ||
                $company->parent_company_id === $user->company_id;
        }

        // Company user può vedere solo la propria company
        return $user->company_id === $company->id;
    }

    public function create(User $user): bool
    {
        // SUPER_ADMIN può sempre creare aziende (main, invited)
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN di San Pietro (PROPRIETARIO) può creare qualsiasi tipo di azienda
        if ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            return true;
        }

        // Altri COMPANY_ADMIN possono creare aziende invited se hanno il permesso
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $user->hasPermissionTo('create companies');
        }

        return false;
    }

    public function update(User $user, Company $company): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // San Pietro (PROPRIETARIO) può modificare TUTTE le aziende
        if ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            return true;
        }

        // Altri Company admin possono modificare la propria company e le sue child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $user->company_id === $company->id ||
                $company->parent_company_id === $user->company_id;
        }

        return false;
    }

    public function delete(User $user, Company $company): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // San Pietro (PROPRIETARIO) può eliminare TUTTE le aziende (tranne se stessa)
        if ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            return $user->company_id !== $company->id; // Non può eliminare se stesso
        }

        // Altri Company admin possono eliminare solo le proprie child companies (non se stessa)
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $company->parent_company_id === $user->company_id;
        }

        return false;
    }

    public function invite(User $user, Company $company): bool
    {
        return $user->hasPermissionTo('invite companies') &&
            $user->company_id === $company->id;
    }
}
