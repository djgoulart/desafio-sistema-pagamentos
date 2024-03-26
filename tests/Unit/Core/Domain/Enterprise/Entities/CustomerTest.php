<?php

namespace Tests\Unit\Core\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Dtos\CustomerDto;
use Core\Domain\Enterprise\Entities\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    private function createCustomerData(): CustomerDto
    {
        return new CustomerDto(
            name: 'John Doe',
            cpfCnpj: '501.141.080-30',
        );
    }

    public function test_customer_attributes_are_correct(): void
    {
        $sut = new Customer(customerData: $this->createCustomerData());
        $customer = $sut->getData();
        // dd($customer);
        $this->assertEquals('John Doe', $customer['name']);
        $this->assertEquals('50114108030', $customer['cpfCnpj']);
    }
}
