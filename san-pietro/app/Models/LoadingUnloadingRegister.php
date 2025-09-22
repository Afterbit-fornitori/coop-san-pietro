<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\BelongsToTenant;

class LoadingUnloadingRegister extends Model
{
    use HasFactory, LogsActivity, BelongsToTenant;

    protected $fillable = [
        'company_id',
        'data_operazione',
        'tipo_operazione',
        'product_id',
        'lotto',
        'kg_reimmersione',
        'kg_piccola',
        'kg_media',
        'kg_grossa',
        'kg_granchio',
        'provenienza_destinazione',
        'transport_document_id',
        'note'
    ];

    protected $casts = [
        'data_operazione' => 'date',
        'kg_reimmersione' => 'decimal:2',
        'kg_piccola' => 'decimal:2',
        'kg_media' => 'decimal:2',
        'kg_grossa' => 'decimal:2',
        'kg_granchio' => 'decimal:2'
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transportDocument()
    {
        return $this->belongsTo(TransportDocument::class);
    }

    // Scopes
    public function scopeCarico($query)
    {
        return $query->where('tipo_operazione', 'CARICO');
    }

    public function scopeScarico($query)
    {
        return $query->where('tipo_operazione', 'SCARICO');
    }

    // Calcola totale kg dell'operazione
    public function getTotaleKgAttribute()
    {
        return $this->kg_reimmersione + $this->kg_piccola + $this->kg_media + $this->kg_grossa + $this->kg_granchio;
    }
}