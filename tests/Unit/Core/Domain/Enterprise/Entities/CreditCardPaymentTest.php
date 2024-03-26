<?php

namespace Tests\Unit\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Dtos\CreditCardHolderInfoDto;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Core\Domain\Enterprise\Entities\CreditCardPayment;
use Core\Domain\Enterprise\Exceptions\EntityValidationException;
use Core\Domain\Enterprise\ValueObjects\CpfCnpj;
use Core\Domain\Enterprise\ValueObjects\CreditCard;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CreditCardPaymentTest extends TestCase
{
    private $paymentData;

    private $creditCard;

    private $creditCardHolderInfo;

    private string $remoteIp;

    protected function setUp(): void
    {
        parent::setUp();

        $this->paymentData = new PaymentDto(
            customerId: 'cus_123',
            value: 123.45,
            remoteIp: '127.0.0.1'
        );

        $this->creditCard = new CreditCard(
            holderName: 'John Doe',
            number: '4111111111111111',
            expiryMonth: '12',
            expiryYear: '2024',
            ccv: '123'
        );

        $this->creditCardHolderInfo = $this->createCreditCardHolderInfo();

    }

    private function createCreditCardHolderInfo(): CreditCardHolderInfoDto
    {
        return new CreditCardHolderInfoDto(
            name: 'John Doe',
            email: 'johndoe@example.com',
            cpfCnpj: new CpfCnpj('123.456.789-09'),
            postalCode: '12345678',
            addressNumber: '123',
            addressComplement: 'Apartment 1',
            phone: '1234567890'
        );
    }

    private function createCreditCardPayment(string $customerId, float $value)
    {
        // dd($value, $customerId);
        $payment = new PaymentDto(customerId: $customerId, value: $value);

        return new CreditCardPayment(
            payment: $payment,
            creditCard: $this->creditCard,
            creditCardHolderInfo: $this->creditCardHolderInfo,
        );
    }

    public function testCreditCardPaymentInitialization()
    {
        $sut = $this->createCreditCardPayment(customerId: 'cus_123', value: 123.45);

        $this->assertInstanceOf(CreditCardPayment::class, $sut);
        $this->assertIsArray($sut->getCreditCard());

        $this->assertArrayHasKey('holderName', $sut->getCreditCard());
        $this->assertArrayHasKey('number', $sut->getCreditCard());
        $this->assertArrayHasKey('expiryMonth', $sut->getCreditCard());
        $this->assertArrayHasKey('expiryYear', $sut->getCreditCard());
        $this->assertArrayHasKey('ccv', $sut->getCreditCard());

        $this->assertEquals('cus_123', $sut->customerId);
        $this->assertEquals(123.45, $sut->value);
    }

    public function testCreditCardPaymentWithZeroValue()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('The payment value must be greater than zero');

        new CreditCardPayment(
            payment: new PaymentDto(customerId: 'cus_123', value: 0),
            creditCard: $this->creditCard,
            creditCardHolderInfo: $this->creditCardHolderInfo,
        );
    }

    public function testCreditCardPaymentWithNegativeValue()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('The payment value must be greater than zero');

        new CreditCardPayment(
            payment: new PaymentDto(customerId: 'cus_123', value: -50),
            creditCard: $this->creditCard,
            creditCardHolderInfo: $this->creditCardHolderInfo,
        );
    }

    public function testCreditCardPaymentWithPastExpiryDate()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid expiry date');

        $pastCreditCard = new CreditCard(
            holderName: 'John Doe',
            number: '4111111111111111',
            expiryMonth: '01',
            expiryYear: (string) (date('Y') - 1),
            ccv: '123'
        );

        new CreditCardPayment(
            payment: $this->paymentData,
            creditCard: $pastCreditCard,
            creditCardHolderInfo: $this->creditCardHolderInfo,
        );
    }

    public function testCreditCardPaymentWithInvalidCardNumber()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid card number');

        $invalidCreditCard = new CreditCard(
            holderName: 'John Doe',
            number: '1234567890123456',
            expiryMonth: '12',
            expiryYear: '2024',
            ccv: '123'
        );

        new CreditCardPayment(
            payment: $this->paymentData,
            creditCard: $invalidCreditCard,
            creditCardHolderInfo: $this->creditCardHolderInfo,
        );
    }

    public function testCreditCardPaymentMissingRequiredFields()
    {
        $this->expectException(EntityValidationException::class);
        $this->expectExceptionMessage('CustomerID is required');

        new CreditCardPayment(
            payment: new PaymentDto(customerId: '', value: 123.45),
            creditCard: $this->creditCard,
            creditCardHolderInfo: $this->creditCardHolderInfo,
        );
    }
}
