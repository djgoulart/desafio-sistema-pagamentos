<?php

namespace Core\Domain\Application\Contracts;

use Core\Domain\Enterprise\Dtos\PaymentDetailsDto;

interface PaymentProcessor
{
    public function processPayment(string $method, PaymentDetailsDto $details);
}
