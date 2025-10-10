<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view clients');
    }

    public function view(User $user, Client $client): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN (San Pietro) può vedere i clienti della propria company e delle child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            if ($user->company?->isSanPietro()) {
                // San Pietro può vedere i propri clienti e quelli delle aziende invitate
                return $user->company_id === $client->company_id ||
                       $client->company?->parent_company_id === $user->company_id;
            }

            // Altri COMPANY_ADMIN possono vedere solo i propri clienti
            return $user->company_id === $client->company_id;
        }

        // COMPANY_USER può vedere solo i clienti della propria company
        return $user->company_id === $client->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create clients') && $user->company_id !== null;
    }

    public function update(User $user, Client $client): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Solo la propria company può modificare i clienti
        return $user->company_id === $client->company_id &&
               $user->hasPermissionTo('edit clients');
    }

    public function delete(User $user, Client $client): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Solo COMPANY_ADMIN può eliminare clienti della propria company
        return $user->hasRole('COMPANY_ADMIN') &&
               $user->company_id === $client->company_id &&
               $user->hasPermissionTo('delete clients');
    }
}