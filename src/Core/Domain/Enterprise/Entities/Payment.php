<?php

namespace Core\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Entities\Traits\EntityTrait;
use Core\Domain\Enterprise\ValueObjects\Uuid;
use Core\Domain\Enterprise\Validation\EntityValidation;
use Core\Domain\Enterprise\Validation\PaymentValidation;
use Core\Domain\Enterprise\Enums\PaymentStatus;
use Core\Domain\Enterprise\Contracts\Transaction;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Datetime;
use Exception;

abstract class Payment
{
    use EntityTrait;

    protected $id;
    protected $customerId;
    protected $externalId;
    protected $value;
    protected $dueDate;
    protected $description;
    protected $status;
    protected $createdAt;

    public function __construct(PaymentDto $paymentAttributes)
    {
        $this->id = $paymentAttributes->id ? new Uuid($paymentAttributes->id) : Uuid::random();
        $this->customerId = $paymentAttributes->customerId;
        $this->externalId = $paymentAttributes->externalId;
        $this->value = $paymentAttributes->value;
        $this->dueDate = $paymentAttributes->dueDate ? $paymentAttributes->dueDate : new DateTime();
        $this->description = $paymentAttributes->description;
        $this->status = $paymentAttributes->status ? $paymentAttributes->status : PaymentStatus::PENDING;
        $this->createdAt = $paymentAttributes->createdAt ? $paymentAttributes->createdAt : new DateTime();

        $this->validate();
    }

    protected function validate()
    {
        EntityValidation::notNull($this->customerId);
        PaymentValidation::validateValue($this->value);
        PaymentValidation::validateDueDate($this->dueDate);
    }

}