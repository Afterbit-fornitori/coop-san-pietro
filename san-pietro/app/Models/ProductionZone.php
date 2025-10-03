<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\BelongsToTenant;

class ProductionZone extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, BelongsToTenant;

    protected $fillable = [
        'company_id',
        'codice',
        'nome',
        'mq',
        'classe_sanitaria',
        'declassificazione_temporanea',
        'data_declassificazione',
        'is_active'
    ];

    protected $casts = [
        'mq' => 'decimal:2',
        'declassificazione_temporanea' => 'boolean',
        'data_declassificazione' => 'date',
        'is_active' => 'boolean'
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

    public function transportDocuments()
    {
        return $this->hasMany(TransportDocument::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDeclassified($query)
    {
        return $query->where('declassificazione_temporanea', true);
    }

    // Verifica se la zona può essere usata per prodotti da consumo
    public function canProduceForConsumption(): bool
    {
        return $this->is_active && !$this->declassificazione_temporanea;
    }
}
