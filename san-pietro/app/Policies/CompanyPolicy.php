<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_companies');
    }

    public function view(User $user, Company $company): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Company admin può vedere la propria company e le sue child companies
        if ($user->hasRole('company_admin')) {
            return $user->company_id === $company->id || 
                   $user->company_id === $company->parent_id;
        }

        // Company user può vedere solo la propria company
        return $user->company_id === $company->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_companies');
    }

    public function update(User $user, Company $company): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Company admin può modificare solo le sue child companies
        if ($user->hasRole('company_admin')) {
            return $user->company_id === $company->parent_id;
        }

        return false;
    }

    public function delete(User $user, Company $company): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Company admin può eliminare solo le sue child companies
        if ($user->hasRole('company_admin')) {
            return $user->company_id === $company->parent_id;
        }

        return false;
    }

    public function invite(User $user, Company $company): bool
    {
        return $user->hasPermissionTo('invite_companies') && 
               $user->company_id === $company->id;
    }
}
