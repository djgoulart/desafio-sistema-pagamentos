<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory, HasUuids;

    protected $autoincrement = false;

    protected $keyType = 'string';

    protected $fillable = [
        'externalId',
        'name',
        'cpfCnpj',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'customerId', 'externalId');
    }
}
