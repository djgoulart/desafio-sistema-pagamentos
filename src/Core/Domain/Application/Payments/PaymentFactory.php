<?php

namespace Core\Domain\Application\Payments;

use Core\Domain\Enterprise\Enums\PaymentMethods;
use Core\Domain\Enterprise\Dtos\PaymentDetailsDto;
use Core\Domain\Enterprise\Entities\Payment;
use Core\Domain\Enterprise\Entities\CreditCardPayment;
use InvalidArgumentException;

class PaymentFactory
{
    public static function createPayment(PaymentMethods $type, PaymentDetailsDto $data): Payment
    {
        if(!$type) {
            throw new InvalidArgumentException('Payment method is required');
        }

        if(!$data->payment) {
            throw new InvalidArgumentException('Payment data is required');
        }

        switch ($type) {
            case PaymentMethods::CREDIT_CARD:
                if(!$data->creditCard) {
                    throw new InvalidArgumentException('Credit card data is required');
                }

                if(!$data->remoteIp) {
                    throw new InvalidArgumentException('Remote IP is required');
                }

                return new CreditCardPayment(
                    payment: $data->payment,
                    creditCard: $data->creditCard,
                    remoteIp: $data->remoteIp,
                );
            default:
                throw new InvalidArgumentException('Invalid payment method');
        }
    }
}
