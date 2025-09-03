<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Production extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'member_id',
        'company_id',
        'week_number',
        'year',
        'micro_price',
        'micro_quantity',
        'standard_price',
        'standard_quantity',
        'notes'
    ];

    protected $casts = [
        'micro_price' => 'decimal:2',
        'micro_quantity' => 'decimal:2',
        'standard_price' => 'decimal:2',
        'standard_quantity' => 'decimal:2'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeForCurrentCompany($query)
    {
        return $query->where('company_id', auth()->user()->company_id);
    }

    public function getMicroTotalAttribute()
    {
        return $this->micro_price * $this->micro_quantity;
    }

    public function getStandardTotalAttribute()
    {
        return $this->standard_price * $this->standard_quantity;
    }

    public function getTotalQuantityAttribute()
    {
        return $this->micro_quantity + $this->standard_quantity;
    }

    public function getTotalAmountAttribute()
    {
        return $this->micro_total + $this->standard_total;
    }
}
