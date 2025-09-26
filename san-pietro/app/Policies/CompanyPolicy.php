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
        if ($user->hasRole('SUPER_ADMIN' || 'COMPANY_ADMIN')) {
            return true;
        }

        // Company admin può vedere la propria company e le sue child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $user->company_id === $company->id ||
                $user->company_id === $company->parent_company_id;
        }

        // Company user può vedere solo la propria company
        return $user->company_id === $company->id;
    }

    public function create(User $user): bool
    {
        // SUPER_ADMIN può sempre creare aziende
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        return $user->hasPermissionTo('create companies');
    }

    public function update(User $user, Company $company): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Company admin può modificare la propria company e le sue child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $user->company_id === $company->id ||
                $user->company_id === $company->parent_company_id;
        }

        return false;
    }

    public function delete(User $user, Company $company): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Company admin può eliminare solo le sue child companies (non se stessa)
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $user->company_id === $company->parent_company_id;
        }

        return false;
    }

    public function invite(User $user, Company $company): bool
    {
        return $user->hasPermissionTo('invite_companies') &&
            $user->company_id === $company->id;
    }
}
