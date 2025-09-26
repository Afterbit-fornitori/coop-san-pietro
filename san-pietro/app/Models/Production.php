<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\BelongsToTenant;

class Production extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, BelongsToTenant;

    protected $fillable = [
        'company_id',
        'production_zone_id',
        'member_id',
        'product_id',
        'production_date',
        'production_type',      // enum: 'internal_reimmersion', 'resale_reimmersion', 'consumption'
        'category',             // enum: 'micro', 'small', 'medium', 'large', 'super'
        'quantity_kg',
        'unit_price',
        'total',
        'status',              // enum: 'available', 'sold', 'reimmersed'
        'notes',
        'transport_document_id', // nullable, connected when used in a DDT
        'weekly_record_id'      // nullable, connected when included in weekly record
    ];

    protected $casts = [
        'production_date' => 'date',
        'quantity_kg' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2'
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

    public function productionZone()
    {
        return $this->belongsTo(ProductionZone::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transportDocument()
    {
        return $this->belongsTo(TransportDocument::class);
    }

    public function weeklyRecord()
    {
        return $this->belongsTo(WeeklyRecord::class);
    }

    // Scopes for filtering productions
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('production_type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('production_date', [$startDate, $endDate]);
    }

    // Calculate production total
    public function calculateTotal()
    {
        $this->total = $this->quantity_kg * $this->unit_price;
        $this->save();
    }

    // Check if production can be used in a DDT
    public function isAvailableForDDT()
    {
        return $this->status === 'available' && !$this->transport_document_id;
    }

    // Associate production with a DDT
    public function assignToDDT(TransportDocument $ddt)
    {
        $this->transport_document_id = $ddt->id;
        $this->status = 'sold';
        $this->save();
    }

    // Add production to weekly record
    public function addToWeeklyRecord(WeeklyRecord $weeklyRecord)
    {
        $this->weekly_record_id = $weeklyRecord->id;
        $this->save();
    }
}
