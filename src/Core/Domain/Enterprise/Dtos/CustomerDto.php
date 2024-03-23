<?php

namespace Core\Domain\Enterprise\Dtos;

class CustomerDto {
    public function __construct(
        public ?string $id = '',
        public ?string $externalId = '',
        public ?string $name = '',
        public ?string $cpfCnpj = '',
        public ?string $createdAt = '',
    ) {}
}
