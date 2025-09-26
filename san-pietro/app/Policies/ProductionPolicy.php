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
        return true;
    }

    public function view(User $user, Production $production): bool
    {
        return $user->company_id === $production->company_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Production $production): bool
    {
        return $user->company_id === $production->company_id;
    }

    public function delete(User $user, Production $production): bool
    {
        return $user->company_id === $production->company_id;
    }
}
