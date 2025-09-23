<?php

namespace App\Policies;

use App\Models\ProductionZone;
use App\Models\User;

class ProductionZonePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view production zones');
    }

    public function view(User $user, ProductionZone $productionZone): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN (San Pietro) può vedere le zone della propria company e delle child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            if ($user->company && $user->company->isMain()) {
                // San Pietro può vedere le proprie zone e quelle delle aziende invitate
                return $user->company_id === $productionZone->company_id ||
                       ($productionZone->company && $productionZone->company->parent_company_id === $user->company_id);
            }

            // Altri COMPANY_ADMIN possono vedere solo le proprie zone
            return $user->company_id === $productionZone->company_id;
        }

        // COMPANY_USER può vedere solo le zone della propria company
        return $user->company_id === $productionZone->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create production zones') && $user->company_id !== null;
    }

    public function update(User $user, ProductionZone $productionZone): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Solo la propria company può modificare le zone
        return $user->company_id === $productionZone->company_id &&
               $user->hasPermissionTo('edit production zones');
    }

    public function delete(User $user, ProductionZone $productionZone): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // Solo COMPANY_ADMIN può eliminare zone della propria company
        return $user->hasRole('COMPANY_ADMIN') &&
               $user->company_id === $productionZone->company_id &&
               $user->hasPermissionTo('delete production zones');
    }
}