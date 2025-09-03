<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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

        // COMPANY_ADMIN (San Pietro) può accedere alla propria azienda e a quelle invitate
        if ($this->isCompanyAdmin() && $this->company) {
            // Può accedere alla propria azienda
            if ($this->company_id === $companyId) {
                return true;
            }
            
            // Può accedere alle aziende che ha invitato (solo se è l'azienda principale)
            if ($this->company->isMain()) {
                $invitedCompany = Company::find($companyId);
                return $invitedCompany && $invitedCompany->parent_company_id === $this->company_id;
            }
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

        if ($this->isCompanyAdmin() && $this->company && $this->company->isMain()) {
            // Restituisce la propria azienda + quelle invitate
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

        if ($user->isCompanyAdmin() && $user->company && $user->company->isMain()) {
            $accessibleCompanyIds = $user->getAccessibleCompanies()->pluck('id');
            return $query->whereIn('company_id', $accessibleCompanyIds);
        }

        return $query->where('company_id', $user->company_id);
    }
}
