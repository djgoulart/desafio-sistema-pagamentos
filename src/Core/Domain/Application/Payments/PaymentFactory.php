<?php

namespace Core\Domain\Application\Payments;

use Core\Domain\Enterprise\Enums\PaymentMethods;
use Core\Domain\Enterprise\Dtos\PaymentDetailsDto;
use Core\Domain\Enterprise\Entities\Payment;
use Core\Domain\Enterprise\Entities\CreditCardPayment;
use InvalidArgumentException;

class PaymentFactory
{
    protected static $methodMap = [];

    public static function registerPaymentMethod(string $method, callable $constructor): void
    {
        self::$methodMap[$method] = $constructor;
    }

    public static function createPayment(PaymentMethods $type, PaymentDetailsDto $data): Payment
    {
        if (!isset(self::$methodMap[$type->value])) {
            throw new InvalidArgumentException('Invalid payment method');
        }

        return call_user_func(self::$methodMap[$type->value], $data);
    }
}
