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