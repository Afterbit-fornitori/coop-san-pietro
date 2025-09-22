<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyInvitation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'inviter_company_id',
        'email',
        'token',
        'nome_azienda',
        'tipo_attivita',
        'settore',
        'permessi',
        'scade_il',
        'stato'
    ];

    protected $casts = [
        'permessi' => 'array',
        'scade_il' => 'datetime',
    ];

    public function inviterCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'inviter_company_id');
    }

    public function isExpired(): bool
    {
        return $this->scade_il->isPast();
    }

    public function isPending(): bool
    {
        return $this->stato === 'pending';
    }
}
