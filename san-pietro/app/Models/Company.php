<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Multitenancy\Models\Tenant;

class Company extends Tenant
{
    use SoftDeletes, LogsActivity;
    
    protected $fillable = [
        'name',
        'parent_company_id',
        'type', // 'master', 'main', 'invited'
        'vat_number',
        'tax_code',
        'address',
        'city',
        'province',
        'zip_code',
        'phone',
        'email',
        'pec',
        'settings',
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
        return $this->hasMany(Company::class, 'parent_company_id');
    }

    public function parentCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'parent_company_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(CompanyInvitation::class, 'inviter_company_id');
    }

    // Scope per aziende master (San Pietro)
    public function scopeMaster($query)
    {
        return $query->where('type', 'master');
    }

    // Scope per aziende principali (San Pietro)
    public function scopeMain($query)
    {
        return $query->where('type', 'main');
    }

    // Scope per aziende invitate
    public function scopeInvited($query)
    {
        return $query->where('type', 'invited');
    }

    // Verifica se è l'azienda master
    public function isMaster(): bool
    {
        return $this->type === 'master';
    }

    // Verifica se è l'azienda principale
    public function isMain(): bool
    {
        return $this->type === 'main';
    }

    // Verifica se è un'azienda invitata
    public function isInvited(): bool
    {
        return $this->type === 'invited';
    }
}
