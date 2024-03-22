<?php

namespace Core\Domain\Enterprise\ValueObjects;

use Core\Domain\Enterprise\Validation\CpfValidation;
use Core\Domain\Enterprise\Validation\CnpjValidation;
use InvalidArgumentException;

class CpfCnpj
{
    public function __construct(
        protected string $value
    ) {
        $this->ensureIsValid($value);
    }

    public function __toString(): string
    {
        return preg_replace('/\D/', '', $this->value);
    }

    private function ensureIsValid(string $value)
    {
        if (!CpfValidation::validate($value) && !CnpjValidation::validate($value)) {
            throw new InvalidArgumentException('Invalid CPF or CNPJ.');
        }
    }
}
