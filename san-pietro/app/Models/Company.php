<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Company extends Tenant
{
    use LogsActivity;
    protected $table = 'companies';
    
    protected $fillable = [
        'name',
        'domain',
        'parent_id',
        'type', // 'parent' o 'child'
        'settings',
        'vat_number',
        'tax_code',
        'address',
        'city',
        'province',
        'zip_code',
        'is_active'
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name', 'domain', 'type', 'vat_number', 'tax_code',
                'address', 'city', 'province', 'zip_code', 'is_active'
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Company has been {$eventName}");
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function childCompanies(): HasMany
    {
        return $this->hasMany(Company::class, 'parent_id');
    }

    public function parentCompany()
    {
        return $this->belongsTo(Company::class, 'parent_id');
    }
}
