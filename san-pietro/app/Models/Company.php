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
        'domain',
        'parent_company_id',
        'type',
        'vat_number',
        'tax_code',
        'address',
        'city',
        'province',
        'zip_code',
        'phone',
        'email',
        'pec',
        'impostazioni',
        'is_active'
    ];

    protected $casts = [
        'impostazioni' => 'array',
        'is_active' => 'boolean'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'domain',
                'type',
                'vat_number',
                'tax_code',
                'address',
                'city',
                'province',
                'zip_code',
                'is_active'
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

    public function weeklyRecords(): HasMany
    {
        return $this->hasMany(WeeklyRecord::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function productionZones(): HasMany
    {
        return $this->hasMany(ProductionZone::class);
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class);
    }

    public function transportDocuments(): HasMany
    {
        return $this->hasMany(TransportDocument::class);
    }

    public function loadingUnloadingRegisters(): HasMany
    {
        return $this->hasMany(LoadingUnloadingRegister::class);
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

    // Verifica se è l'azienda principale (master o main)
    public function isMain(): bool
    {
        return in_array($this->type, ['master', 'main']);
    }

    // Verifica se è un'azienda invitata
    public function isInvited(): bool
    {
        return $this->type === 'invited';
    }
}
