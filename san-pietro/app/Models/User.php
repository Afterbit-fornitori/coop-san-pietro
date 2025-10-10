<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'company_id', 'is_active'])
            ->setDescriptionForEvent(fn(string $eventName) => "User has been {$eventName}");
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // Metodi helper per i ruoli multi-tenant
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('SUPER_ADMIN');
    }

    public function isCompanyAdmin(): bool
    {
        return $this->hasRole('COMPANY_ADMIN');
    }

    public function isCompanyUser(): bool
    {
        return $this->hasRole('COMPANY_USER');
    }

    // Verifica se può accedere ai dati di una specifica azienda
    public function canAccessCompany($companyId): bool
    {
        // SUPER_ADMIN può accedere a tutto
        if ($this->isSuperAdmin()) {
            return true;
        }

        // COMPANY_ADMIN di San Pietro può accedere a TUTTE le aziende
        if ($this->isCompanyAdmin() && $this->company && $this->company->isSanPietro()) {
            return true;
        }

        // Altri COMPANY_ADMIN possono accedere alla propria azienda e alle sub-aziende
        if ($this->isCompanyAdmin() && $this->company) {
            // Può accedere alla propria azienda
            if ($this->company_id === $companyId) {
                return true;
            }

            // Può accedere alle aziende figlie (sub-aziende assegnate)
            $targetCompany = Company::find($companyId);
            return $targetCompany && $targetCompany->parent_company_id === $this->company_id;
        }

        // COMPANY_USER può accedere solo alla propria azienda
        if ($this->isCompanyUser()) {
            return $this->company_id === $companyId;
        }

        return false;
    }

    // Restituisce tutte le aziende accessibili dall'utente
    public function getAccessibleCompanies()
    {
        if ($this->isSuperAdmin()) {
            return Company::all();
        }

        // San Pietro vede TUTTE le aziende
        if ($this->isCompanyAdmin() && $this->company?->isSanPietro()) {
            return Company::all();
        }

        // Altri COMPANY_ADMIN vedono solo la propria azienda + sub-aziende assegnate
        if ($this->isCompanyAdmin()) {
            return Company::where('id', $this->company_id)
                ->orWhere('parent_company_id', $this->company_id)
                ->get();
        }

        // COMPANY_USER può vedere solo la propria azienda
        return Company::where('id', $this->company_id)->get();
    }

    // Scope per filtrare utenti per azienda accessibile
    public function scopeAccessibleToUser($query, User $user)
    {
        if ($user->isSuperAdmin()) {
            return $query;
        }

        // San Pietro vede TUTTI gli utenti
        if ($user->isCompanyAdmin() && $user->company?->isSanPietro()) {
            return $query;
        }

        // Altri COMPANY_ADMIN vedono solo utenti della propria azienda + sub-aziende
        if ($user->isCompanyAdmin()) {
            $accessibleCompanyIds = $user->getAccessibleCompanies()->pluck('id');
            return $query->whereIn('company_id', $accessibleCompanyIds);
        }

        return $query->where('company_id', $user->company_id);
    }
}
