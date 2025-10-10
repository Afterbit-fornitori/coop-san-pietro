<?php

namespace App\Policies;

use App\Models\Production;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // SUPER_ADMIN può vedere tutto
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN e COMPANY_USER possono vedere solo le proprie produzioni
        return $user->hasRole('COMPANY_ADMIN') || $user->hasRole('COMPANY_USER');
    }

    public function view(User $user, Production $production): bool
    {
        // SUPER_ADMIN può vedere tutto
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // San Pietro può vedere produzioni di aziende child
        if ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            return $production->company_id === $user->company_id ||
                $production->company?->parent_company_id === $user->company_id;
        }

        // Altri utenti vedono solo le proprie produzioni
        return $user->company_id === $production->company_id;
    }

    public function create(User $user): bool
    {
        // SUPER_ADMIN può creare per qualsiasi azienda
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN e COMPANY_USER possono creare per la propria azienda
        return ($user->hasRole('COMPANY_ADMIN') || $user->hasRole('COMPANY_USER')) &&
            $user->company_id !== null &&
            $user->hasPermissionTo('create production');
    }

    public function update(User $user, Production $production): bool
    {
        // SUPER_ADMIN può modificare tutto
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // San Pietro può modificare produzioni di aziende child
        if ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            $canModify = $production->company_id === $user->company_id ||
                $production->company?->parent_company_id === $user->company_id;
            return $canModify && $user->hasPermissionTo('edit production');
        }

        // Altri utenti modificano solo le proprie produzioni
        return $user->company_id === $production->company_id &&
            $user->hasPermissionTo('edit production');
    }

    public function delete(User $user, Production $production): bool
    {
        // SUPER_ADMIN può eliminare tutto
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Solo la propria azienda può eliminare le proprie produzioni
        // San Pietro NON può eliminare produzioni di aziende child (solo proprie)
        return $user->company_id === $production->company_id &&
            $user->hasPermissionTo('delete production');
    }
}
