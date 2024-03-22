<?php

namespace Core\Domain\Enterprise\Dtos;

use Core\Domain\Enterprise\Enums\PaymentStatus;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Core\Domain\Enterprise\Dtos\CreditCardDto;

class PaymentDetailsDto {
    public function __construct(
        public ?PaymentDto $payment = null,
        public ?CreditCardDto $creditCard = null,
        public ?string $remoteIp = null,
    ) {}
}
