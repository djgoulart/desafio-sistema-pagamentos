<?php

namespace Tests\Unit\Core\Domain\Enterprise\Entities;

use PHPUnit\Framework\TestCase;
use Core\Domain\Enterprise\Entities\Customer;
use Ramsey\Uuid\Uuid;

class CustomerTest extends TestCase
{

    public function test_customer_attributes_are_correct(): void
    {
        $customer = new Customer(
            name: 'John Doe',
            cpfCnpj: '12345678901'
        );

        $this->assertNotEmpty($customer->createdAt());
        $this->assertNotEmpty($customer->id());
        $this->assertEquals('John Doe', $customer->name);
        $this->assertEquals('12345678901', $customer->cpfCnpj);
    }

    public function test_customer_data_can_be_edited(): void
    {
        $uuid = (string) Uuid::uuid4()->toString();

        $customer = new Customer(
            id: $uuid,
            name: 'John Doe',
            cpfCnpj: '12345678901',
            createdAt: '2024-10-10 10:10:10'
        );

        $customer->update(
            name: 'John Doe Updated',
        );

        $this->assertEquals($uuid, $customer->id());
        $this->assertEquals('John Doe Updated', $customer->name);
    }
}
