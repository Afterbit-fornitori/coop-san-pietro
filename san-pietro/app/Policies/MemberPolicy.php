<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;

class MemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view members');
    }

    public function view(User $user, Member $member): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN (San Pietro) può vedere i membri della propria company e delle child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            if ($user->company?->isSanPietro()) {
                // San Pietro può vedere i propri membri e quelli delle aziende invitate
                return $user->company_id === $member->company_id ||
                       $member->company?->parent_company_id === $user->company_id;
            }

            // Altri COMPANY_ADMIN possono vedere solo i propri membri
            return $user->company_id === $member->company_id;
        }

        // COMPANY_USER può vedere solo i membri della propria company
        return $user->company_id === $member->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create members') && $user->company_id !== null;
    }

    public function update(User $user, Member $member): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Solo la propria company può modificare i soci
        return $user->company_id === $member->company_id &&
               $user->hasPermissionTo('edit members');
    }

    public function delete(User $user, Member $member): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Solo COMPANY_ADMIN può eliminare soci della propria company
        return $user->hasRole('COMPANY_ADMIN') &&
               $user->company_id === $member->company_id &&
               $user->hasPermissionTo('delete members');
    }

    public function viewWeeklyRecords(User $user, Member $member): bool
    {
        // Stesse regole del view standard per i record settimanali
        return $this->view($user, $member);
    }

    public function manageWeeklyRecords(User $user, Member $member): bool
    {
        // Solo la propria company può gestire i record settimanali
        return $user->company_id === $member->company_id;
    }
}