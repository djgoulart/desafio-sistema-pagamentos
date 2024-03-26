<?php

namespace Tests\Unit\Domain\Enterprise\Validation;

use Core\Domain\Enterprise\Validation\CreditCardValidation;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CreditCardValidationTest extends TestCase
{
    public function testValidateHolderNameWithValidName()
    {
        $validName = 'John Doe';
        $this->assertNull(CreditCardValidation::validateHolderName($validName));
    }

    public function testValidateHolderNameWithEmptyName()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Holder name is required.');
        CreditCardValidation::validateHolderName('');
    }

    public function testValidateHolderNameWithShortName()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Holder name must be at least 3 characters long.');
        CreditCardValidation::validateHolderName('Jo');
    }

    public function testValidateExpiryDateWithValidDate()
    {
        $futureYear = date('Y') + 1;
        $this->assertNull(CreditCardValidation::validateExpiryDate('12', (string) $futureYear));
    }

    public function testValidateExpiryDateWithPastDate()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid expiry date.');
        $pastYear = date('Y') - 1;
        CreditCardValidation::validateExpiryDate('01', (string) $pastYear);
    }

    public function testValidateExpiryDateWithInvalidMonth()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid expiry date.');
        $futureYear = date('Y') + 1;
        CreditCardValidation::validateExpiryDate('13', (string) $futureYear);
    }

    public function testValidateExpiryDateWithInvalidYear()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid expiry date.');
        CreditCardValidation::validateExpiryDate('12', 'abc');
    }

    public function testValidateCvvWithValidCvv()
    {
        $this->assertNull(CreditCardValidation::validateCcv('123'));
        $this->assertNull(CreditCardValidation::validateCcv('1234'));
    }

    public function testValidateCvvWithInvalidCvvLength()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid CCV.');
        CreditCardValidation::validateCcv('12');
    }

    public function testValidateCvvWithInvalidCvvCharacters()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid CCV.');
        CreditCardValidation::validateCcv('12a3');
    }

    public function testValidateCvvWithTooLongCvv()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid CCV.');
        CreditCardValidation::validateCcv('12345');
    }

    public function testIsValidCardNumberWithValidNumber()
    {
        $validCardNumber = '4532015112830366';
        $reflection = new \ReflectionClass(CreditCardValidation::class);
        $method = $reflection->getMethod('isValidCardNumber');
        $method->setAccessible(true);

        $result = $method->invokeArgs(null, [$validCardNumber]);
        $this->assertTrue($result);
    }

    public function testIsValidCardNumberWithInvalidNumber()
    {
        $invalidCardNumber = '4532015112830367';
        $reflection = new \ReflectionClass(CreditCardValidation::class);
        $method = $reflection->getMethod('isValidCardNumber');
        $method->setAccessible(true);

        $result = $method->invokeArgs(null, [$invalidCardNumber]);
        $this->assertFalse($result);
    }

    public function testValidatecardNumberWithValidNumber()
    {
        $validCardNumber = '4532015112830366';
        $this->assertNull(CreditCardValidation::validateCardNumber($validCardNumber));
    }

    public function testValidateCardNumberWithInvalidNumber()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid card number.');

        $invalidCardNumber = '4532015112830367';
        CreditCardValidation::validateCardNumber($invalidCardNumber);
    }

    public function testValidateCardNumerWithInvalidFormat()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid card number.');

        $invalidCardNumber = '4532a015112830366';
        CreditCardValidation::validateCardNumber($invalidCardNumber);
    }

    public function testValidateCardNumerIncomplete()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid card number.');

        $invalidCardNumber = '453209';
        CreditCardValidation::validateCardNumber($invalidCardNumber);
    }

    public function testValidateCardNumerIsEmpty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid card number.');

        $invalidCardNumber = '';
        CreditCardValidation::validateCardNumber($invalidCardNumber);
    }
}
