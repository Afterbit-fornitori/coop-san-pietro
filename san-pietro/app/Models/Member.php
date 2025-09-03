<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\BelongsToTenant;

class Member extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, BelongsToTenant;

    protected $fillable = [
        'name',
        'tax_code',
        'birth_date',
        'birth_place',
        'rpm_code',
        'registration_date',
        'business_name',
        'plant_location',
        'rpm_notes',
        'vessel_notes',
        'company_id'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'registration_date' => 'date'
    ];

    // Definisce quale campo utilizzare per il tenant
    public function getTenantKeyName(): string
    {
        return 'company_id';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }
}
