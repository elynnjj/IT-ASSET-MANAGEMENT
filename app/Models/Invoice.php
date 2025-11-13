<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoiceID',
        'fileName',
    ];

    protected $primaryKey = 'invoiceID';

    public $incrementing = false;

    protected $keyType = 'string';

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'invoiceID', 'invoiceID');
    }
}
