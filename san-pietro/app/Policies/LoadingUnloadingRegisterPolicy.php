<?php

namespace App\Policies;

use App\Models\LoadingUnloadingRegister;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class LoadingUnloadingRegisterPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view loading unloading');
    }

    public function view(User $user, LoadingUnloadingRegister $register): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            \Log::info('LoadingUnloadingPolicy view: SUPER_ADMIN - TRUE');
            return true;
        }

        // COMPANY_ADMIN (San Pietro) può vedere i registri della propria company e delle child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            if ($user->company?->isSanPietro()) {
                $canView = $user->company_id === $register->company_id ||
                          $register->company?->parent_company_id === $user->company_id;

                \Log::info('LoadingUnloadingPolicy view: San Pietro', [
                    'user_company_id' => $user->company_id,
                    'register_company_id' => $register->company_id,
                    'result' => $canView
                ]);

                return $canView;
            }

            $canView = $user->company_id === $register->company_id;

            \Log::info('LoadingUnloadingPolicy view: Other COMPANY_ADMIN', [
                'user_company_id' => $user->company_id,
                'register_company_id' => $register->company_id,
                'result' => $canView
            ]);

            return $canView;
        }

        // COMPANY_USER può vedere solo i registri della propria company
        $canView = $user->company_id === $register->company_id;

        \Log::info('LoadingUnloadingPolicy view: COMPANY_USER', [
            'user_company_id' => $user->company_id,
            'register_company_id' => $register->company_id,
            'result' => $canView
        ]);

        return $canView;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create loading unloading') && $user->company_id !== null;
    }

    public function update(User $user, LoadingUnloadingRegister $register): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN può modificare i propri registri
        if ($user->hasRole('COMPANY_ADMIN')) {
            // San Pietro può modificare tutti i registri
            if ($user->company?->isSanPietro()) {
                $canModify = $user->company_id === $register->company_id ||
                            $register->company?->parent_company_id === $user->company_id;

                Log::info('LoadingUnloadingPolicy update: San Pietro', [
                    'user_company_id' => $user->company_id,
                    'register_company_id' => $register->company_id,
                    'result' => $canModify
                ]);

                return $canModify;
            }

            // Altri COMPANY_ADMIN possono modificare solo i propri registri
            return $user->company_id === $register->company_id;
        }

        // COMPANY_USER può modificare solo i propri registri
        return $user->company_id === $register->company_id;
    }

    public function delete(User $user, LoadingUnloadingRegister $register): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN può eliminare i propri registri
        if ($user->hasRole('COMPANY_ADMIN')) {
            // San Pietro può eliminare tutti i registri
            if ($user->company?->isSanPietro()) {
                $canDelete = $user->company_id === $register->company_id ||
                            $register->company?->parent_company_id === $user->company_id;

                Log::info('LoadingUnloadingPolicy delete: San Pietro', [
                    'user_company_id' => $user->company_id,
                    'register_company_id' => $register->company_id,
                    'result' => $canDelete
                ]);

                return $canDelete;
            }

            // Altri COMPANY_ADMIN possono eliminare solo i propri registri
            return $user->company_id === $register->company_id;
        }

        // COMPANY_USER non può eliminare (solo COMPANY_ADMIN)
        return false;
    }
}
