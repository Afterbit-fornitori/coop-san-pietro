<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;

class MemberPolicy
{
    public function viewAny(User $user): bool
    {
        // Tutti i ruoli possono visualizzare la lista membri (filtrata dal loro scope)
        return $user->hasAnyRole(['SUPER_ADMIN', 'COMPANY_ADMIN', 'COMPANY_USER']);
    }

    public function view(User $user, Member $member): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN (San Pietro) può vedere i membri della propria company e delle child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            if ($user->company && $user->company->isMain()) {
                // San Pietro può vedere i propri membri e quelli delle aziende invitate
                return $user->company_id === $member->company_id || 
                       ($member->company && $member->company->parent_company_id === $user->company_id);
            }
            
            // Altri COMPANY_ADMIN possono vedere solo i propri membri
            return $user->company_id === $member->company_id;
        }

        // COMPANY_USER può vedere solo i membri della propria company
        return $user->company_id === $member->company_id;
    }

    public function create(User $user): bool
    {
        // Tutti i ruoli possono creare membri nella propria company
        return $user->hasAnyRole(['SUPER_ADMIN', 'COMPANY_ADMIN', 'COMPANY_USER']) && 
               $user->company_id !== null;
    }

    public function update(User $user, Member $member): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN può modificare i membri della propria company
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $user->company_id === $member->company_id;
        }

        // COMPANY_USER può modificare i membri della propria company
        if ($user->hasRole('COMPANY_USER')) {
            return $user->company_id === $member->company_id;
        }

        return false;
    }

    public function delete(User $user, Member $member): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Solo COMPANY_ADMIN può eliminare membri della propria company
        if ($user->hasRole('COMPANY_ADMIN')) {
            return $user->company_id === $member->company_id;
        }

        return false;
    }

    public function viewProductions(User $user, Member $member): bool
    {
        // Stesse regole del view standard
        return $this->view($user, $member);
    }

    public function manageProductions(User $user, Member $member): bool
    {
        // Solo la propria company può gestire le produzioni
        return $user->company_id === $member->company_id;
    }
}