<?php

namespace Tests\Unit\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Entities\Payment;
use Core\Domain\Enterprise\Enums\PaymentStatus;
use Core\Domain\Enterprise\Exceptions\EntityValidationException;
use Core\Domain\Enterprise\ValueObjects\Uuid;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Tests\TestCase;
use DateTime;

class ConcretePayment extends Payment
{}

class PaymentTest extends TestCase
{
    public function testConstructSetsDefaultValues()
    {
        $dto = new PaymentDto(
            customerId: 'cus_123',
            value: 123.45
        );

        $payment = new ConcretePayment(paymentAttributes: $dto);

        $this->assertInstanceOf(Uuid::class, $payment->id);
        $this->assertInstanceOf(DateTime::class, $payment->createdAt);
        $this->assertInstanceOf(DateTime::class, $payment->dueDate);
        $this->assertEquals(PaymentStatus::PENDING, $payment->status);
    }

    public function testConstructWithValues()
    {
        $dto = new PaymentDto(
            id: Uuid::random(),
            customerId: 'customer123',
            externalId: 'external123',
            value: 100.0,
            dueDate: new DateTime('+1 day'),
            description: 'Test payment',
            status: PaymentStatus::CONFIRMED,
            createdAt: new DateTime('-1 day'),
        );

        $payment = new ConcretePayment(paymentAttributes: $dto);

        $this->assertEquals($dto->id, $payment->id());
        $this->assertEquals($dto->customerId, $payment->customerId);
        $this->assertEquals($dto->externalId, $payment->externalId);
        $this->assertEquals($dto->value, $payment->value);
        $this->assertEquals($dto->description, $payment->description);
        $this->assertEquals($dto->status, $payment->status);
        $this->assertEquals(
            $dto->dueDate->format('Y-m-d'), $payment->dueDate->format('Y-m-d')
        );
        $this->assertEquals(
            $dto->createdAt->format('Y-m-d'), $payment->createdAt->format('Y-m-d')
        );
    }

    public function testValidationFailure()
    {
        $this->expectException(EntityValidationException::class);
        $dto = new PaymentDto(
            customerId: 'cus_123',
            value: -100.0
        );
        new ConcretePayment(paymentAttributes: $dto);
    }

}
