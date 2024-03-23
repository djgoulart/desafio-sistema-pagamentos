<?php

namespace Core\Domain\Application\Contracts;

use Core\Domain\Enterprise\Dtos\PaymentDetailsDto;

interface PaymentProcessor
{
    /**
     * Process a payment using a specified payment method and payment details.
     *
     * @param string $method The payment method to be used for processing the payment.
     * @param PaymentDetailsDto $details An object that contains the payment details such as amount, and customer information.
     * @return void
     */
    public function processPayment(string $method, PaymentDetailsDto $details);
}
