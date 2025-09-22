<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Traits\BelongsToTenant;

class Client extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, BelongsToTenant;

    protected $fillable = [
        'company_id',
        'business_name',
        'vat_number',
        'tax_code',
        'address',
        'city',
        'postal_code',
        'province',
        'pec',
        'phone',
        'sdi_code',
        'note',
        'is_active'
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

    // Accessor for complete address
    public function getFullAddressAttribute()
    {
        $address = collect([$this->address, $this->city, $this->province, $this->postal_code])
            ->filter()
            ->implode(', ');
        return $address;
    }
}
