<?php

namespace Core\Domain\Application\Contracts;

use Core\Domain\Enterprise\Dtos\PaymentDetailsDto;
use Core\Domain\Enterprise\Entities\Payment;

interface PixProcessor
{
    /**
     * Get the pixQrCode image and payload of a payment.
     *
     * @param string $paymentId The the ID of the payment.
     * @return PixQrCodeDto
     */
    public function getPixData(Payment $payment);
}
