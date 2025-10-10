<?php

namespace App\Policies;

use App\Models\TransportDocument;
use App\Models\User;

class TransportDocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view transport documents');
    }

    public function view(User $user, TransportDocument $document): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN (San Pietro) può vedere i documenti della propria company e delle child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            if ($user->company?->isSanPietro()) {
                // San Pietro può vedere i propri documenti e quelli delle aziende invitate
                return $user->company_id === $document->company_id ||
                       $document->company?->parent_company_id === $user->company_id;
            }

            // Altri COMPANY_ADMIN possono vedere solo i propri documenti
            return $user->company_id === $document->company_id;
        }

        // COMPANY_USER può vedere solo i documenti della propria company
        return $user->company_id === $document->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create transport documents') && $user->company_id !== null;
    }

    public function update(User $user, TransportDocument $document): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Solo la propria company può modificare i documenti
        return $user->company_id === $document->company_id &&
               $user->hasPermissionTo('edit transport documents');
    }

    public function delete(User $user, TransportDocument $document): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Solo COMPANY_ADMIN può eliminare documenti della propria company
        return $user->hasRole('COMPANY_ADMIN') &&
               $user->company_id === $document->company_id &&
               $user->hasPermissionTo('delete transport documents');
    }

    public function print(User $user, TransportDocument $document): bool
    {
        // Tutti possono stampare documenti che possono vedere
        return $this->view($user, $document) &&
               $user->hasPermissionTo('print transport documents');
    }
}