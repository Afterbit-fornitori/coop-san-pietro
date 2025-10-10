<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        if (
            $user->hasRole('SUPER_ADMIN') ||
            ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro())
        ) {
            return true;
        }

        // Altri COMPANY_ADMIN possono vedere utenti solo se hanno il permesso
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $user->hasPermissionTo('view users');
        }

        return false;
    }

    public function view(User $user, User $model): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // San Pietro può vedere TUTTI gli utenti
        if ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            return true;
        }

        // Altri Company admin possono vedere gli utenti della propria company e delle child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $user->company_id === $model->company_id ||
                $model->company?->parent_company_id === $user->company_id;
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

        // San Pietro può modificare TUTTI gli utenti
        if ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            return true;
        }

        // Altri Company admin possono modificare gli utenti della propria company e delle child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $user->company_id === $model->company_id ||
                $model->company?->parent_company_id === $user->company_id;
        }

        // Gli altri utenti possono modificare solo il proprio profilo
        return $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // San Pietro può eliminare TUTTI gli utenti (tranne se stesso)
        if ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            return $user->id !== $model->id; // Non può eliminare se stesso
        }

        // Altri Company admin possono eliminare gli utenti delle child companies, ma non quelli della propria company
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $model->company?->parent_company_id === $user->company_id;
        }

        return false;
    }
}
