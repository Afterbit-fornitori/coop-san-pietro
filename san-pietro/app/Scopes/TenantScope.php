<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Spatie\Multitenancy\Models\Tenant;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // SUPER_ADMIN non ha company_id e può vedere tutto
        if (auth()->check() && auth()->user()->hasRole('SUPER_ADMIN')) {
            return;
        }

        $currentTenant = app('currentTenant');

        if ($currentTenant) {
            $builder->where($model->getTable() . '.company_id', $currentTenant->id);
        }
    }
}