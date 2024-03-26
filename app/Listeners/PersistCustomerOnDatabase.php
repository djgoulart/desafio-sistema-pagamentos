<?php

namespace App\Listeners;

use App\Events\CustomerCreated;

class PersistCustomerOnDatabase
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CustomerCreated $event): void
    {
        //
    }
}
