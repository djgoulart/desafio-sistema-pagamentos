<?php

namespace Core\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Dtos\PaymentDto;

class BoletoPayment extends Payment
{
    public function __construct(
        protected PaymentDto $payment,
        protected string $boletoUrl = '',
    ) {
        parent::__construct(paymentAttributes: $payment);

        $this->validate();
    }

    public function getId(): string
    {
        return parent::getId();
        // return $this->id;
    }

    public function getBoletoUrl(): string
    {
        return $this->boletoUrl;
    }

    public function setBoletoUrl(string $boletoUrl): void
    {
        $this->boletoUrl = $boletoUrl;
    }

    protected function validate()
    {
        parent::validate();
    }
}
