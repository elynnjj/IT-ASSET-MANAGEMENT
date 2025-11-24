<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignAsset extends Model
{
    protected $fillable = [
        'assignID',
        'checkoutDate',
        'checkinDate',
        'assetID',
        'userID',
    ];

    protected $primaryKey = 'assignID';

    protected function casts(): array
    {
        return [
            'checkoutDate' => 'date',
            'checkinDate' => 'date',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'assetID', 'assetID');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
