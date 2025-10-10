<?php

namespace App\Policies;

use App\Models\CompanyInvitation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyInvitationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Solo SUPER_ADMIN o San Pietro possono vedere inviti
        return $user->hasRole('SUPER_ADMIN') ||
               ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CompanyInvitation $companyInvitation): bool
    {
        // SUPER_ADMIN può vedere tutto
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // San Pietro può vedere tutti gli inviti
        if ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            return true;
        }

        // Altri COMPANY_ADMIN vedono solo i propri inviti inviati
        return $user->company_id === $companyInvitation->inviter_company_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Solo SUPER_ADMIN o San Pietro possono creare inviti
        return $user->hasRole('SUPER_ADMIN') ||
               ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro());
    }

    /**
     * Determine whether the user can update the model (resend).
     */
    public function update(User $user, CompanyInvitation $companyInvitation): bool
    {
        // SUPER_ADMIN può modificare tutto
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // San Pietro può modificare tutti gli inviti
        if ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            return true;
        }

        // Altri COMPANY_ADMIN possono modificare solo i propri inviti
        return $user->company_id === $companyInvitation->inviter_company_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CompanyInvitation $companyInvitation): bool
    {
        // SUPER_ADMIN può eliminare tutto
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // San Pietro può eliminare tutti gli inviti
        if ($user->hasRole('COMPANY_ADMIN') && $user->company?->isSanPietro()) {
            return true;
        }

        // Altri COMPANY_ADMIN possono eliminare solo i propri inviti
        return $user->company_id === $companyInvitation->inviter_company_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CompanyInvitation $companyInvitation): bool
    {
        // Solo SUPER_ADMIN può ripristinare inviti eliminati
        return $user->hasRole('SUPER_ADMIN');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CompanyInvitation $companyInvitation): bool
    {
        // Solo SUPER_ADMIN può eliminare permanentemente
        return $user->hasRole('SUPER_ADMIN');
    }
}
