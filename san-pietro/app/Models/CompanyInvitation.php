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
        'company_name',
        'business_type',
        'sector',
        'permissions',
        'expires_at',
        'status'
    ];

    protected $casts = [
        'permissions' => 'array',
        'expires_at' => 'datetime',
    ];

    public function inviterCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'inviter_company_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
