<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportDocumentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transport_document_id',
        'product_id',
        'quantita_kg',
        'numero_colli',
        'prezzo_unitario',
        'totale'
    ];

    protected $casts = [
        'quantita_kg' => 'decimal:2',
        'prezzo_unitario' => 'decimal:2',
        'totale' => 'decimal:2'
    ];

    // Relazioni
    public function transportDocument()
    {
        return $this->belongsTo(TransportDocument::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Calcola automaticamente il totale quando si salvano quantità o prezzo
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->totale = $item->quantita_kg * $item->prezzo_unitario;
        });

        static::saved(function ($item) {
            // Aggiorna i totali del documento padre
            $item->transportDocument->calcolaTotali();
        });

        static::deleted(function ($item) {
            // Aggiorna i totali del documento padre anche quando viene eliminato un item
            $item->transportDocument->calcolaTotali();
        });
    }
}