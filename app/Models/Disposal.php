<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposal extends Model
{
    protected $fillable = [
        'disposeID',
        'dispStatus',
        'dispDate',
        'assetID',
    ];

    protected $primaryKey = 'disposeID';

    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'dispDate' => 'date',
        ];
    }

    /**
     * Get the asset that this disposal belongs to.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'assetID', 'assetID');
    }
}

