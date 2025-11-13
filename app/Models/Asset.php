<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
