<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Member extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

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

    public function scopeForCurrentCompany($query)
    {
        return $query->where('company_id', auth()->user()->company_id);
    }

    public function productions()
    {
        return $this->hasMany(Production::class);
    }
}
