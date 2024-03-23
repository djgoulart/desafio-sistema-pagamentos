<?php

namespace Core\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Entities\Traits\EntityTrait;
use Core\Domain\Enterprise\Validation\EntityValidation;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Datetime;

class PixPayment extends Payment
{
    public function __construct(
        protected PaymentDto $payment,
        protected string $invoiceUrl = '',
    ) {
        parent::__construct(paymentAttributes: $payment);

        $this->validate();
    }

    protected function validate()
    {
        parent::validate();
    }
}
