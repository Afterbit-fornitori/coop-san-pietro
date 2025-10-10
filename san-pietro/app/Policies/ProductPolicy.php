<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view products');
    }

    public function view(User $user, Product $product): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        // COMPANY_ADMIN (San Pietro) può vedere i prodotti della propria company e delle child companies
        if ($user->hasRole('COMPANY_ADMIN')) {
            if ($user->company?->isSanPietro()) {
                return $user->company_id === $product->company_id ||
                       $product->company?->parent_company_id === $user->company_id;
            }

            return $user->company_id === $product->company_id;
        }

        // COMPANY_USER può vedere solo i prodotti della propria company
        return $user->company_id === $product->company_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create products') && $user->company_id !== null;
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        return $user->company_id === $product->company_id &&
               $user->hasPermissionTo('edit products');
    }

    public function delete(User $user, Product $product): bool
    {
        if ($user->hasRole('SUPER_ADMIN')) {
            return true;
        }

        return $user->hasRole('COMPANY_ADMIN') &&
               $user->company_id === $product->company_id &&
               $user->hasPermissionTo('delete products');
    }
}
