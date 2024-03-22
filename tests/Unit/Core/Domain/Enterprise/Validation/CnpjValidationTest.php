<?php

namespace Tests\Unit\Domain\Enterprise\Validation;


use PHPUnit\Framework\TestCase;
use Core\Domain\Enterprise\Validation\CnpjValidation;

class CnpjValidationTest extends TestCase
{
    public function testValidateWithValidCnpj()
    {
        $this->assertTrue(CnpjValidation::validate('99.618.392/0001-14'));
    }

    public function testValidateWithInvalidCnpjNumberSequence()
    {
        $this->assertFalse(CnpjValidation::validate('11.111.111/1111-11'));
    }

    public function testValidateWithInvalidCnpjLength()
    {
        $this->assertFalse(CnpjValidation::validate('11.222.333/0001-8'));

        $this->assertFalse(CnpjValidation::validate('11.222.333/0001-8111'));
    }

    public function testValidateWithInvalidCharacters()
    {
        $this->assertFalse(CnpjValidation::validate('11.222.333/0001-8a'));
    }

    public function testValidateWithIncorrectCheckDigits()
    {
        $this->assertFalse(CnpjValidation::validate('11.222.333/0001-82'));
    }

    public function testValidateWithValidCnpjFromDifferentRegions()
    {
        $this->assertTrue(CnpjValidation::validate('35.806.009/0001-79'));
        $this->assertTrue(CnpjValidation::validate('33.975.727/0001-43'));
    }

    public function testValidateWithSpecialCharacters()
    {
        $this->assertTrue(CnpjValidation::validate('48.292.310/0001-53'));
        $this->assertFalse(CnpjValidation::validate('34.028.316/0001-07!'));
    }

    public function testValidateWithEmptyString()
    {
        $this->assertFalse(CnpjValidation::validate(''));
    }

    public function testValidateWithNull()
    {
        $this->assertFalse(CnpjValidation::validate(null));
    }

}
