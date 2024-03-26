<?php

namespace Core\Domain\Enterprise\Dtos;

class CreditCardDto
{
    public function __construct(
        public string $holderName,
        public string $number,
        public string $expiryMonth,
        public string $expiryYear,
        public string $ccv,
    ) {
    }
}
