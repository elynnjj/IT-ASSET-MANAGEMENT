<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'assetID',
        'assetType',
        'serialNum',
        'model',
        'ram',
        'storage',
        'purchaseDate',
        'osVer',
        'processor',
        'status',
        'installedSoftware',
        'invoiceID',
    ];

    protected $primaryKey = 'assetID';

    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'purchaseDate' => 'date',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoiceID', 'invoiceID');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(AssignAsset::class, 'assetID', 'assetID');
    }

    public function currentAssignment(): ?AssignAsset
    {
        return $this->assignments()->whereNull('checkinDate')->latest('checkoutDate')->first();
    }

    public function disposals(): HasMany
    {
        return $this->hasMany(Disposal::class, 'assetID', 'assetID');
    }

    public function itRequests(): HasMany
    {
        return $this->hasMany(ITRequest::class, 'assetID', 'assetID');
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'assetID', 'assetID');
    }
}
