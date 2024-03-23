<?php

namespace Core\Domain\Enterprise\Dtos;

use Core\Domain\Enterprise\Enums\PaymentStatus;
use Datetime;

class PaymentUpdateDto {
    public function __construct(
        public ?string $externalId = null,
        public PaymentStatus|string|null $status = null,
        public ?string $invoiceUrl = null,
        public ?string $transactionReceiptUrl = null,
    ) {}
}
