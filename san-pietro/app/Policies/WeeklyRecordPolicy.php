<?php

namespace App\Policies;

use App\Models\WeeklyRecord;
use App\Models\User;

class WeeklyRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view weekly records');
    }

    public function view(User $user, WeeklyRecord $weeklyRecord): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN (San Pietro) può vedere i record della propria company e delle child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            if ($user->company?->isSanPietro()) {
                // San Pietro può vedere i propri record e quelli delle aziende invitate
                return $user->company_id === $weeklyRecord->company_id ||
                       $weeklyRecord->company?->parent_company_id === $user->company_id;
            }

            // Altri COMPANY_ADMIN possono vedere solo i propri record
            return $user->company_id === $weeklyRecord->company_id;
        }

        // COMPANY_USER può vedere solo i record della propria company
        return $user->company_id === $weeklyRecord->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create weekly records') && $user->company_id !== null;
    }

    public function update(User $user, WeeklyRecord $weeklyRecord): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Solo la propria company può modificare i record
        return $user->company_id === $weeklyRecord->company_id &&
               $user->hasPermissionTo('edit weekly records');
    }

    public function delete(User $user, WeeklyRecord $weeklyRecord): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Solo COMPANY_ADMIN può eliminare record della propria company
        return $user->hasRole('COMPANY_ADMIN') &&
               $user->company_id === $weeklyRecord->company_id &&
               $user->hasPermissionTo('delete weekly records');
    }
}