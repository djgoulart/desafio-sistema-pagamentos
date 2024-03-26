<?php

namespace Tests\Unit\Core\Domain\Enterprise\Validation;

use Core\Domain\Enterprise\Exceptions\EntityValidationException;
use Core\Domain\Enterprise\Validation\PaymentValidation;
use Tests\TestCase;

class PaymentValidationTest extends TestCase
{
    public function testValidateValueWithValidFloat()
    {
        $validValue = 100.50;
        $this->assertNull(PaymentValidation::validateValue($validValue));
    }

    public function testValidateValueWithZero()
    {
        $this->expectException(EntityValidationException::class);
        $zeroValue = 0.0;
        PaymentValidation::validateValue($zeroValue);
    }

    public function testValidateValueWithNegativeFloat()
    {
        $this->expectException(EntityValidationException::class);
        $negativeValue = -100.50;
        PaymentValidation::validateValue($negativeValue);
    }

    public function testValidateValueWithNonFloat()
    {
        $this->expectException(EntityValidationException::class);
        $nonFloatValue = '100.50'; // String represents a number, but it isn't of type float.
        PaymentValidation::validateValue($nonFloatValue);
    }

    public function testValidateDueDateWithFutureDateTime()
    {
        $futureDate = new \DateTime('+1 day');
        $this->assertNull(PaymentValidation::validateDueDate($futureDate));
    }

    public function testValidateDueDateWithFutureDateString()
    {
        $futureDate = (new \DateTime('+1 day'))->format('Y-m-d');
        $this->assertNull(PaymentValidation::validateDueDate($futureDate));
    }

    public function testValidateDueDateWithPastDateTime()
    {
        $this->expectException(EntityValidationException::class);
        $pastDate = new \DateTime('-1 day');
        PaymentValidation::validateDueDate($pastDate);
    }

    public function testValidateDueDateWithPastDateString()
    {
        $this->expectException(EntityValidationException::class);
        $pastDate = (new \DateTime('-1 day'))->format('Y-m-d');
        PaymentValidation::validateDueDate($pastDate);
    }

    public function testValidateDueDateWithInvalidFormat()
    {
        $this->expectException(EntityValidationException::class);
        $invalidDate = 'invalid-date-string';
        PaymentValidation::validateDueDate($invalidDate);
    }

    public function testValidateDueDateWithNonDateTimeNonString()
    {
        $this->expectException(EntityValidationException::class);
        PaymentValidation::validateDueDate(12345); // Passando um inteiro, por exemplo.
    }
}
