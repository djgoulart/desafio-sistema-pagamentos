<?php

namespace Tests\Unit\Domain\Enterprise\Entities;

use PHPUnit\Framework\TestCase;
use Core\Domain\Enterprise\Entities\CreditCardHolder;
use Core\Domain\Enterprise\ValueObjects\CpfCnpj;
use Core\Domain\Enterprise\Exceptions\EntityValidationException;

class CreditCardHolderTest extends TestCase
{
    public function testSuccessfulConstruction()
    {
        $holder = new CreditCardHolder(
            name: 'John Doe',
            email: 'johndoe@example.com',
            cpfCnpj: new CpfCnpj('123.456.789-09'),
            postalCode: '12345678',
            addressNumber: '123',
            addressComplement: 'Apartment 1',
            phone: '1234567890'
        );

        $this->assertInstanceOf(CreditCardHolder::class, $holder);
    }

    public function testGetData()
    {
        $name = 'John Doe';
        $email = 'johndoe@example.com';
        $cpfCnpj = new CpfCnpj('109.522.620-70');
        $postalCode = '12345678';
        $addressNumber = '123';
        $addressComplement = 'Apartment 1';
        $phone = '1234567890';

        $holder = new CreditCardHolder(
            name: $name,
            email: $email,
            cpfCnpj: $cpfCnpj,
            postalCode: $postalCode,
            addressNumber: $addressNumber,
            addressComplement: $addressComplement,
            phone: $phone
        );

        $data = $holder->getData();

        $this->assertEquals($name, $data['name']);
        $this->assertEquals($email, $data['email']);
        $this->assertEquals((string)$cpfCnpj, $data['cpfCnpj']);
        $this->assertEquals($postalCode, $data['postalCode']);
        $this->assertEquals($addressNumber, $data['addressNumber']);
        $this->assertEquals($addressComplement, $data['addressComplement']);
        $this->assertEquals($phone, $data['phone']);
    }

    public function testValidationFailsWithInvalidData()
    {
        $this->expectException(EntityValidationException::class);
        new CreditCardHolder(
            name: '',
            email: 'johndoe@example.com',
            cpfCnpj: new CpfCnpj('109.522.620-70'),
            postalCode: '12345678',
            addressNumber: '123',
            addressComplement: 'Apartment 1',
            phone: '1234567890'
        );
    }
}
