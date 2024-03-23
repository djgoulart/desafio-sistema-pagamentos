<?php

namespace Core\Domain\Enterprise\Dtos;

class CreditCardHolderInfoDto {
    public function __construct(
        public ?string $name = '',
        public ?string $email = '',
        public ?string $cpfCnpj = '',
        public ?string $postalCode = '',
        public ?string $addressNumber = '',
        public ?string $addressComplement = '',
        public ?string $phone = '',
    ) {}
}
