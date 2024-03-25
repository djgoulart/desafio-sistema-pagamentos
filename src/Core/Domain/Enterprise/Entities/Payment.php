<?php

namespace Core\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Entities\Traits\EntityTrait;
use Core\Domain\Enterprise\ValueObjects\Uuid;
use Core\Domain\Enterprise\Validation\EntityValidation;
use Core\Domain\Enterprise\Validation\PaymentValidation;
use Core\Domain\Enterprise\Enums\PaymentStatus;
use Core\Domain\Enterprise\Contracts\Transaction;
use Core\Domain\Enterprise\Dtos\PaymentDto;
use Core\Domain\Enterprise\Dtos\PaymentUpdateDto;
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
    protected $invoiceUrl;
    protected $transactionReceiptUrl;
    protected $createdAt;
    protected $remoteIp;

    public function __construct(PaymentDto $paymentAttributes)
    {
        $this->id = $paymentAttributes->id ? new Uuid($paymentAttributes->id) : Uuid::random();
        $this->customerId = $paymentAttributes->customerId;
        $this->externalId = $paymentAttributes->externalId;
        $this->value = $paymentAttributes->value;
        $this->dueDate = $paymentAttributes->dueDate ? $paymentAttributes->dueDate : new DateTime();
        $this->description = $paymentAttributes->description;
        $this->status = $paymentAttributes->status ? $paymentAttributes->status : PaymentStatus::PENDING;
        $this->invoiceUrl = $paymentAttributes->invoiceUrl;
        $this->transactionReceiptUrl = $paymentAttributes->transactionReceiptUrl;
        $this->createdAt = $paymentAttributes->createdAt ? $paymentAttributes->createdAt : new DateTime();
        $this->remoteIp = $paymentAttributes->remoteIp;

        $this->validate();
    }

    public function updatePaymentDetails(PaymentUpdateDto $updateData)
    {
        if (!PaymentStatus::tryFrom($updateData->status)) {
            throw new Exception("Invalid payment status provided.");
        }

        if(!empty($this->extenalId)) {
            throw new Exception("Payment already processed.");
        }

        $this->status = $updateData->status;
        $this->externalId = $updateData->externalId;
        $this->invoiceUrl = $updateData->invoiceUrl;
        $this->transactionReceiptUrl = $updateData->transactionReceiptUrl;
    }

    public function setId(string $id) {
        $this->id = new Uuid($id);
        $this->validate();
    }

    public function getData()
    {
        return [
            'id' => $this->id,
            'customerId' => $this->customerId,
            'externalId' => $this->externalId,
            'value' => $this->value,
            'dueDate' => $this->dueDate,
            'description' => $this->description,
            'status' => $this->status,
            'invoiceUrl' => $this->invoiceUrl,
            'transactionReceiptUrl' => $this->transactionReceiptUrl,
            'createdAt' => $this->createdAt
        ];
    }

    protected function validate()
    {
        EntityValidation::notNull($this->customerId, "CustomerID is required");
        PaymentValidation::validateValue($this->value);
        PaymentValidation::validateDueDate($this->dueDate);
    }

}
