<?php

namespace Core\Domain\Enterprise\Dtos;

class PixQrCodeDto
{
    public function __construct(
        public bool $success = false,
        public ?string $encodedImage = null,
        public ?string $payload = null,
        public ?string $expirationDate = null,
    ) {
    }
}
