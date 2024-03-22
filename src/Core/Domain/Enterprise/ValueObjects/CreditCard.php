<?php

namespace Core\Domain\Enterprise\ValueObjects;

use Core\Domain\Enterprise\Validation\CreditCardValidation;

class CreditCard
{
    public function __construct(
        protected string $holderName = '',
        protected string $number = '',
        protected string $expiryMonth = '',
        protected string $expiryYear = '',
        protected string $ccv = '',
    ) {
        $this->ensureIsValid();
    }

    public function getData(): array
    {
        return [
            'holderName' => $this->holderName,
            'expiryMonth' => $this->expiryMonth,
            'expiryYear' => $this->expiryYear,
            'number' => $this->number,
            'ccv' => $this->ccv,
        ];
    }

    private function ensureIsValid(): void
    {
        CreditCardValidation::validateHolderName($this->holderName);
        CreditCardValidation::validateCardNumber($this->number);
        CreditCardValidation::validateExpiryDate($this->expiryMonth, $this->expiryYear);
        CreditCardValidation::validateCcv($this->ccv);
    }
}
