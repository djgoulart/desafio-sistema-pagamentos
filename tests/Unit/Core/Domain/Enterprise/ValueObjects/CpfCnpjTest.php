<?php

namespace Tests\Unit\Domain\Enterprise\ValueObjects;

use PHPUnit\Framework\TestCase;
use Core\Domain\Enterprise\ValueObjects\CpfCnpj;
use InvalidArgumentException;
use Core\Domain\Enterprise\Validation\CpfValidation;
use Core\Domain\Enterprise\Validation\CnpjValidation;

class CpfCnpjTest extends TestCase
{
    public function testConstructWithValidCpf()
    {
        $validCpf = '347.560.350-00';
        $cpfCnpj = new CpfCnpj($validCpf);
        $this->assertEquals(preg_replace('/\D/', '', $validCpf), (string)$cpfCnpj);
    }

    public function testConstructWithValidCnpj()
    {
        $validCnpj = '76.892.465/0001-66';
        $cpfCnpj = new CpfCnpj($validCnpj);
        $this->assertEquals(preg_replace('/\D/', '', $validCnpj), (string)$cpfCnpj);
    }

    public function testConstructWithInvalidCpf()
    {
        $this->expectException(InvalidArgumentException::class);
        new CpfCnpj('123.456.789-00');
    }

    public function testConstructWithInvalidCnpj()
    {
        $this->expectException(InvalidArgumentException::class);
        new CpfCnpj('12.345.678/9012-00');
    }

    public function testToStringMethod()
    {
        $validCpf = '731.361.000-93';
        $cpfCnpj = new CpfCnpj($validCpf);
        $this->assertEquals('73136100093', (string)$cpfCnpj);
    }
}
