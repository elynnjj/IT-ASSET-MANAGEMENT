<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ITRequest extends Model
{
    protected $table = 'it_requests';

    protected $fillable = [
        'requestDate',
        'title',
        'requestDesc',
        'status',
        'assetID',
        'requesterID',
        'approverID',
    ];

    protected $primaryKey = 'requestID';

    public $incrementing = true;

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            'requestDate' => 'date',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'assetID', 'assetID');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requesterID', 'userID');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approverID', 'userID');
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'requestID', 'requestID');
    }
}

