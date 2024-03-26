<?php

namespace Core\Domain\Enterprise\Dtos;

class PaymentDetailsDto
{
    public function __construct(
        public ?PaymentDto $payment = null,
        public ?CreditCardDto $creditCard = null,
        public ?CreditCardHolderInfoDto $creditCardHolderInfo = null,
        public ?string $remoteIp = null,
    ) {
    }
}
