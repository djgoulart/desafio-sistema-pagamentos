<?php

namespace Core\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Dtos\PaymentDto;

class PixPayment extends Payment
{
    public function __construct(
        protected PaymentDto $payment,
        protected string $qrCode = '',
        protected string $expirationDate = '',
        protected string $payload = ''
    ) {
        parent::__construct(paymentAttributes: $payment);

        $this->validate();
    }

    public function getPixData(): array
    {
        return [
            'qrCode' => $this->qrCode,
            'expirationDate' => $this->expirationDate,
            'payload' => $this->payload,
        ];
    }

    public function setPixData(string $qrCode, string $expirationDate, string $payload): void
    {
        $this->qrCode = $qrCode;
        $this->expirationDate = $expirationDate;
        $this->payload = $payload;
    }

    protected function validate()
    {
        parent::validate();
    }
}
