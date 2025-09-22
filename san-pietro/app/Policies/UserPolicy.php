<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view users');
    }

    public function view(User $user, User $model): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Company admin può vedere gli utenti della sua company e delle child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $user->company_id === $model->company_id ||
                ($model->company && $model->company->parent_company_id === $user->company_id);
        }

        // Gli altri utenti possono vedere solo il proprio profilo
        return $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create users');
    }

    public function update(User $user, User $model): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Company admin può modificare gli utenti della sua company e delle child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $user->company_id === $model->company_id ||
                ($model->company && $model->company->parent_company_id === $user->company_id);
        }

        // Gli altri utenti possono modificare solo il proprio profilo
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Company admin può eliminare gli utenti delle child companies, ma non quelli della propria company
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $model->company && $model->company->parent_company_id === $user->company_id;
        }

        return false;
    }
}
