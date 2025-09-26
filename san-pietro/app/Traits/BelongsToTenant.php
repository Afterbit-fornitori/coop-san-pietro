<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope());
        
        static::creating(function (Model $model) {
            // Se è SUPER_ADMIN e sta creando esplicitamente, non assegnare automaticamente la company
            if (auth()->check() && auth()->user()->hasRole('SUPER_ADMIN') && $model->company_id !== null) {
                return;
            }

            $currentTenant = app('currentTenant');
            if ($currentTenant && !$model->company_id) {
                $model->company_id = $currentTenant->id;
            }
        });
    }
    
    public function getTenantKeyName(): string
    {
        return 'company_id';
    }
}