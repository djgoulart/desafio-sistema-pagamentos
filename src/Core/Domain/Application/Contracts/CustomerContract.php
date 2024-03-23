<?php

namespace Core\Domain\Application\Contracts;

use Core\Domain\Enterprise\Dtos\CustomerDto;

interface CustomerContract
{
    /**
     * Create a Customer.
     *
     * @param CustomerDto $customer An object that contains the customer information.
     * @return void
     */
    public function createCustomer(CustomerDto $customer);
}
