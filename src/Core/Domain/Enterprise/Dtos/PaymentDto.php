<?php

namespace Core\Domain\Enterprise\Dtos;

use Core\Domain\Enterprise\Enums\PaymentStatus;
use Datetime;

class PaymentDto {
    public function __construct(
        public ?string $id = null,
        public ?string $customerId = null,
        public ?string $externalId = null,
        public ?string $paymentMethod = null,
        public ?float $value = null,
        public string|DateTime|null $dueDate = null,
        public ?string $description = null,
        public ?PaymentStatus $status = null,
        public ?string $invoiceUrl = null,
        public ?string $transactionReceiptUrl = null,
        public string|DateTime|null $createdAt = null,
        public ?string $remoteIp = null,
    ) {}
}
