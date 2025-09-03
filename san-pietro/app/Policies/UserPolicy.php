<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_users');
    }

    public function view(User $user, User $model): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Company admin può vedere gli utenti della sua company e delle child companies
        if ($user->hasRole('company_admin')) {
            return $user->company_id === $model->company_id || 
                   $model->company->parent_id === $user->company_id;
        }

        // Gli altri utenti possono vedere solo il proprio profilo
        return $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_users');
    }

    public function update(User $user, User $model): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Company admin può modificare gli utenti della sua company e delle child companies
        if ($user->hasRole('company_admin')) {
            return $user->company_id === $model->company_id || 
                   $model->company->parent_id === $user->company_id;
        }

        // Gli altri utenti possono modificare solo il proprio profilo
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Company admin può eliminare gli utenti della sua company e delle child companies
        if ($user->hasRole('company_admin')) {
            return $user->company_id === $model->company_id || 
                   $model->company->parent_id === $user->company_id;
        }

        return false;
    }
}
