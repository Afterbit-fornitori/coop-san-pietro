<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\BelongsToTenant;

class Product extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, BelongsToTenant;

    protected $fillable = [
        'company_id',
        'codice',
        'nome_scientifico',
        'nome_commerciale',
        'specie',
        'pezzatura',
        'destinazione',
        'prezzo_base',
        'unita_misura',
        'is_active'
    ];

    protected $casts = [
        'prezzo_base' => 'decimal:2',
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

    public function transportDocumentItems()
    {
        return $this->hasMany(TransportDocumentItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('attivo', true);
    }

    public function scopeBySpecie($query, $specie)
    {
        return $query->where('specie', $specie);
    }

    public function scopeByDestinazione($query, $destinazione)
    {
        return $query->where('destinazione', $destinazione);
    }

    // Accessor per nome completo
    public function getNomeCompletoAttribute()
    {
        $parts = collect([
            $this->nome_commerciale,
            $this->pezzatura,
            $this->destinazione
        ])->filter()->implode(' - ');

        return $parts;
    }
}
