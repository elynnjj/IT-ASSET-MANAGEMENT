<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    protected $fillable = [
        'mainDate',
        'mainDesc',
        'assetID',
        'requestID',
    ];

    protected $primaryKey = 'mainID';

    public $incrementing = true;

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            'mainDate' => 'date',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'assetID', 'assetID');
    }

    public function itRequest(): BelongsTo
    {
        return $this->belongsTo(ITRequest::class, 'requestID', 'requestID');
    }
}

