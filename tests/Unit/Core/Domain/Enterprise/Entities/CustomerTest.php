<?php

namespace Tests\Unit\Core\Domain\Enterprise\Entities;

use PHPUnit\Framework\TestCase;
use Core\Domain\Enterprise\Entities\Customer;
use Core\Domain\Enterprise\Dtos\CustomerDto;
use Ramsey\Uuid\Uuid;

class CustomerTest extends TestCase
{
    private function createCustomerData(): CustomerDto {
        return new CustomerDto(
            name: 'John Doe',
            cpfCnpj: '501.141.080-30',
        );
    }

    public function test_customer_attributes_are_correct(): void
    {
        $sut = new Customer(customerData: $this->createCustomerData());
        $customer = $sut->getData();

        $this->assertNotEmpty($customer['createdAt']);
        $this->assertNotEmpty($customer['id']);
        $this->assertEquals('John Doe', $customer['name']);
        $this->assertEquals('50114108030', $customer['cpfCnpj']);
    }

    public function test_customer_data_can_be_edited(): void
    {
        $sut = new Customer($this->createCustomerData());

        $sut->update(
            name: 'John Doe Updated',
        );

        $customer = $sut->getData();

        $this->assertEquals('John Doe Updated', $customer['name']);
    }
}
