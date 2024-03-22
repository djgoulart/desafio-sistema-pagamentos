<?php

namespace Tests\Unit\Domain\Enterprise\Validation;


use PHPUnit\Framework\TestCase;
use Core\Domain\Enterprise\Validation\CpfValidation;

class CpfValidationTest extends TestCase
{
    public function testValidateWithValidCpf()
    {
        $this->assertTrue(CpfValidation::validate('123.456.789-09'));
    }

    public function testValidateWithInvalidCpfNumberSequence()
    {
        $this->assertFalse(CpfValidation::validate('111.111.111-11'));
    }

    public function testValidateWithInvalidCpfLength()
    {
        $this->assertFalse(CpfValidation::validate('123.456.789-09123'));
    }

    public function testValidateWithInvalidCharacters()
    {
        // We sanitize the input before validating it, so it should return true.
        $this->assertTrue(CpfValidation::validate('123.456.789-09a'));

        $this->assertFalse(CpfValidation::validate('12x.456.789-09'));
        $this->assertFalse(CpfValidation::validate('123.4c6.789-09'));
        $this->assertFalse(CpfValidation::validate('123.456.7b9-09'));

    }

    public function testValidateWithValidCpfFromDifferentRegions()
    {
        $this->assertTrue(CpfValidation::validate('329.983.722-28')); // AC
        $this->assertTrue(CpfValidation::validate('949.817.444-04')); // AL
        $this->assertTrue(CpfValidation::validate('375.015.512-78')); // AM
        $this->assertTrue(CpfValidation::validate('817.725.372-76')); // AP
        $this->assertTrue(CpfValidation::validate('675.780.935-41')); // BA
        $this->assertTrue(CpfValidation::validate('334.487.363-68')); // CE
        $this->assertTrue(CpfValidation::validate('851.143.751-72')); // DF
        $this->assertTrue(CpfValidation::validate('287.469.287-58')); // ES
    }

    public function testValidateWithIncorrectCheckDigits()
    {
        $this->assertFalse(CpfValidation::validate('123.456.789-00'));
    }

    public function testValidateWithEmptyString()
    {
        $this->assertFalse(CpfValidation::validate(''));
    }

    public function testValidateWithNull()
    {
        $this->assertFalse(CpfValidation::validate(null));
    }

    public function testValidateWithInvalidLength()
    {
        $this->assertFalse(CpfValidation::validate('1234567890'));
        $this->assertFalse(CpfValidation::validate('123456789012'));
    }

}
