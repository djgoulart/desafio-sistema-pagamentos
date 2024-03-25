<?php

namespace Core\Domain\Application\Contracts;

use Core\Domain\Enterprise\Entities\Payment;

interface PaymentRepository
{
    public function create(Payment $payment): Payment;
    public function update(Payment $payment, array $data);
    public function findById(string $id);
    public function findByExternalId(string $externalId);
}
