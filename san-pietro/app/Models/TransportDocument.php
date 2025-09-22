<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\BelongsToTenant;

class TransportDocument extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, BelongsToTenant;

    protected $fillable = [
        'company_id',
        'client_id',
        'member_id',
        'production_zone_id',
        'serie',
        'numero',
        'anno',
        'data_documento',
        'ora_partenza',
        'data_raccolta',
        'tipo_documento',
        'stato',
        'causale_trasporto',
        'mezzo_trasporto',
        'annotazioni',
        'totale_imponibile',
        'iva',
        'totale_documento'
    ];

    protected $casts = [
        'data_documento' => 'date',
        'ora_partenza' => 'datetime:H:i',
        'data_raccolta' => 'date',
        'totale_imponibile' => 'decimal:2',
        'iva' => 'decimal:2',
        'totale_documento' => 'decimal:2'
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

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function productionZone()
    {
        return $this->belongsTo(ProductionZone::class);
    }

    public function items()
    {
        return $this->hasMany(TransportDocumentItem::class);
    }

    public function loadingUnloadingRegisters()
    {
        return $this->hasMany(LoadingUnloadingRegister::class);
    }

    // Scopes
    public function scopeByStato($query, $stato)
    {
        return $query->where('stato', $stato);
    }

    public function scopeByTipo($query, $tipo)
    {
        return $query->where('tipo_documento', $tipo);
    }

    public function scopeByAnno($query, $anno)
    {
        return $query->where('anno', $anno);
    }

    // Metodo per generare numero progressivo
    public static function getNextNumber($company_id, $serie, $anno)
    {
        $lastNumber = static::where('company_id', $company_id)
            ->where('serie', $serie)
            ->where('anno', $anno)
            ->max('numero');

        return ($lastNumber ?? 0) + 1;
    }

    // Accessor per numero documento completo
    public function getNumeroCompletoAttribute()
    {
        return "{$this->serie}{$this->numero}/{$this->anno}";
    }

    // Calcola totale documento da items
    public function calcolaTotali()
    {
        $this->totale_imponibile = $this->items->sum('totale');
        $importo_iva = $this->totale_imponibile * ($this->iva / 100);
        $this->totale_documento = $this->totale_imponibile + $importo_iva;
        $this->save();
    }
}