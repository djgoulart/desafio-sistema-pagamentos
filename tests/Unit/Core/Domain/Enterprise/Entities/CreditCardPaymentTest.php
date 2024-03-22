<?php

namespace Tests\Unit\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Entities\CreditCardPayment;
use Core\Domain\Enterprise\ValueObjects\CreditCard;
use Core\Domain\Enterprise\Enums\PaymentStatus;
use Core\Domain\Enterprise\Exceptions\EntityValidationException;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use PHPUnit\Framework\TestCase;
use DateTime;

class CreditCardPaymentTest extends TestCase
{
    private $paymentData;
    private $creditCard;
    private string $remoteIp;

    protected function setUp(): void
    {
        parent::setUp();

        $this->paymentData = new PaymentDto(
            customerId: 'cus_123',
            value: 123.45
        );

        $this->creditCard = new CreditCard(
            holderName: 'John Doe',
            number: '4111111111111111',
            expiryMonth: '12',
            expiryYear: '2024',
            ccv: '123'
        );

        $this->remoteIp = '127.0.0.1';
    }

    public function testCreditCardPaymentInitialization()
    {
        $sut = new CreditCardPayment(
            payment: $this->paymentData,
            creditCard: $this->creditCard,
            remoteIp: '127.0.0.1'
        );

        $this->assertInstanceOf(CreditCardPayment::class, $sut);
        $this->assertIsArray($sut->getCreditCard());

        $this->assertArrayHasKey('holderName', $sut->getCreditCard());
        $this->assertArrayHasKey('number', $sut->getCreditCard());
        $this->assertArrayHasKey('expiryMonth', $sut->getCreditCard());
        $this->assertArrayHasKey('expiryYear', $sut->getCreditCard());
        $this->assertArrayHasKey('ccv', $sut->getCreditCard());

        $this->assertEquals('cus_123', $sut->customerId);
        $this->assertEquals(123.45, $sut->value);
        $this->assertEquals('127.0.0.1', $sut->remoteIp);
    }

    public function testCreditCardPaymentValidationFailure()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('Remote IP should not be null');

        new CreditCardPayment(
            payment: $this->paymentData,
            creditCard: $this->creditCard,
            remoteIp:''
        );
    }

}
