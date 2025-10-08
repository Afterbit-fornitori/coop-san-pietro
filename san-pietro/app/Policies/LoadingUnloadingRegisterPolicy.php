<?php

namespace App\Policies;

use App\Models\LoadingUnloadingRegister;
use App\Models\User;

class LoadingUnloadingRegisterPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view loading unloading');
    }

    public function view(User $user, LoadingUnloadingRegister $register): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN (San Pietro) può vedere i registri della propria company e delle child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            if ($user->company && $user->company->isMain()) {
                return $user->company_id === $register->company_id ||
                       ($register->company && $register->company->parent_company_id === $user->company_id);
            }

            return $user->company_id === $register->company_id;
        }

        // COMPANY_USER può vedere solo i registri della propria company
        return $user->company_id === $register->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create loading unloading') && $user->company_id !== null;
    }

    public function update(User $user, LoadingUnloadingRegister $register): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // San Pietro (main company) può modificare i registri delle proprie aziende e quelle invitate
        if ($user->hasRole('COMPANY_ADMIN') && $user->company && $user->company->isMain()) {
            $canModify = $user->company_id === $register->company_id ||
                        ($register->company && $register->company->parent_company_id === $user->company_id);
            return $canModify && $user->hasPermissionTo('edit loading unloading');
        }

        return $user->company_id === $register->company_id &&
               $user->hasPermissionTo('edit loading unloading');
    }

    public function delete(User $user, LoadingUnloadingRegister $register): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // San Pietro (main company) può eliminare i registri delle proprie aziende e quelle invitate
        if ($user->hasRole('COMPANY_ADMIN') && $user->company && $user->company->isMain()) {
            $canDelete = $user->company_id === $register->company_id ||
                        ($register->company && $register->company->parent_company_id === $user->company_id);
            return $canDelete && $user->hasPermissionTo('delete loading unloading');
        }

        return $user->hasRole('COMPANY_ADMIN') &&
               $user->company_id === $register->company_id &&
               $user->hasPermissionTo('delete loading unloading');
    }
}
