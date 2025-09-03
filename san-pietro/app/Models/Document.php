<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\BelongsToTenant;

class Document extends Model
{
    use SoftDeletes, LogsActivity, BelongsToTenant;

    protected $fillable = [
        'title',
        'path',
        'type',
        'mime_type',
        'size',
        'description',
        'company_id'
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    // Definisce quale campo utilizzare per il tenant
    public function getTenantKeyName(): string
    {
        return 'company_id';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'type', 'description'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
