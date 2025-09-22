<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\BelongsToTenant;

class WeeklyRecord extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, BelongsToTenant;

    protected $fillable = [
        'company_id',
        'member_id',
        'year',
        'week',
        'start_date',
        'end_date',
        'invoice_number',
        // Internal Reimmersion
        'kg_micro_internal_reimmersion',
        'price_micro_internal_reimmersion',
        'kg_small_internal_reimmersion',
        'price_small_internal_reimmersion',
        // Resale Reimmersion
        'kg_micro_resale_reimmersion',
        'price_micro_resale_reimmersion',
        'kg_small_resale_reimmersion',
        'price_small_resale_reimmersion',
        // Direct Consumption
        'kg_medium_consumption',
        'price_medium_consumption',
        'kg_large_consumption',
        'price_large_consumption',
        'kg_super_consumption',
        'price_super_consumption',
        // Calculations
        'taxable_amount',
        'advance_paid',
        'withholding_tax',
        'profis',
        'bank_transfer'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'kg_micro_internal_reimmersion' => 'decimal:2',
        'price_micro_internal_reimmersion' => 'decimal:2',
        'kg_small_internal_reimmersion' => 'decimal:2',
        'price_small_internal_reimmersion' => 'decimal:2',
        'kg_micro_resale_reimmersion' => 'decimal:2',
        'price_micro_resale_reimmersion' => 'decimal:2',
        'kg_small_resale_reimmersion' => 'decimal:2',
        'price_small_resale_reimmersion' => 'decimal:2',
        'kg_medium_consumption' => 'decimal:2',
        'price_medium_consumption' => 'decimal:2',
        'kg_large_consumption' => 'decimal:2',
        'price_large_consumption' => 'decimal:2',
        'kg_super_consumption' => 'decimal:2',
        'price_super_consumption' => 'decimal:2',
        'taxable_amount' => 'decimal:2',
        'advance_paid' => 'decimal:2',
        'withholding_tax' => 'decimal:2',
        'profis' => 'decimal:2',
        'bank_transfer' => 'decimal:2'
    ];

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

    // Relazioni
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // Calcoli automatici (replicano le formule Excel)
    public function getTotalMicroInternalAttribute()
    {
        return $this->kg_micro_internal_reimmersion * $this->price_micro_internal_reimmersion;
    }

    public function getTotalSmallInternalAttribute()
    {
        return $this->kg_small_internal_reimmersion * $this->price_small_internal_reimmersion;
    }

    public function getTotalMicroResaleAttribute()
    {
        return $this->kg_micro_resale_reimmersion * $this->price_micro_resale_reimmersion;
    }

    public function getTotalSmallResaleAttribute()
    {
        return $this->kg_small_resale_reimmersion * $this->price_small_resale_reimmersion;
    }

    public function getTotalMediumAttribute()
    {
        return $this->kg_medium_consumption * $this->price_medium_consumption;
    }

    public function getTotalLargeAttribute()
    {
        return $this->kg_large_consumption * $this->price_large_consumption;
    }

    public function getTotalSuperAttribute()
    {
        return $this->kg_super_consumption * $this->price_super_consumption;
    }

    public function getQuintalsReimmersionAttribute()
    {
        $kg_totali = $this->kg_micro_internal_reimmersion + $this->kg_small_internal_reimmersion +
                     $this->kg_micro_resale_reimmersion + $this->kg_small_resale_reimmersion;
        return $kg_totali / 100;
    }

    public function getQuintalsConsumptionAttribute()
    {
        $kg_totali = $this->kg_medium_consumption + $this->kg_large_consumption + $this->kg_super_consumption;
        return $kg_totali / 100;
    }
}