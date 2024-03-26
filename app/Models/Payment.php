<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $autoincrement = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'externalId',
        'value',
        'dueDate',
        'description',
        'status',
        'invoiceUrl',
        'transactionReceiptUrl',
        'remoteIp',
        'creditCardNumber',
        'creditCardHolderName',
        'creditCardHolderEmail',
        'creditCardHolderPhone',
        'creditCardHolderCpfCnpj',
        'creditCardHolderPostalCode',
        'creditCardHolderAddressNumber',
        'creditCardHolderAddressComplement',
        'boletoUrl',
        'customerId',
        'paymentMethod',
        'pixQrCode',
        'pixPayload',
    ];

    protected function casts(): array
    {
        return [
            'dueDate' => 'datetime:Y-m-d',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customerId', 'externalId');
    }
}
