<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'fileName',
        'filePath',
    ];

    protected $primaryKey = 'invoiceID';

    public $incrementing = true;

    protected $keyType = 'int';

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'invoiceID', 'invoiceID');
    }
}
