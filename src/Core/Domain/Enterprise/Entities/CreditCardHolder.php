<?php

namespace Core\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Validation\EntityValidation;
use Core\Domain\Enterprise\ValueObjects\CpfCnpj;

class CreditCardHolder
{
    public function __construct(
        protected string $name = '',
        protected string $email = '',
        protected CpfCnpj|string $cpfCnpj = '',
        protected string $postalCode = '',
        protected string $addressNumber = '',
        protected string $addressComplement = '',
        protected string $phone = '',
    ) {
        $this->cpfCnpj = $this->cpfCnpj ?? new CpfCnpj($this->cpfCnpj);

        $this->validate();
    }

    public function getData()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'cpfCnpj' => (string) $this->cpfCnpj,
            'postalCode' => $this->postalCode,
            'addressNumber' => $this->addressNumber,
            'addressComplement' => $this->addressComplement,
            'phone' => $this->phone,
        ];
    }

    private function validate()
    {
        EntityValidation::notNull($this->name);
        EntityValidation::strMaxLength($this->name);
        EntityValidation::strMinLength($this->name);
        EntityValidation::notNull($this->postalCode);
        EntityValidation::notNull($this->addressNumber);
        EntityValidation::notNull($this->phone);
    }
}
