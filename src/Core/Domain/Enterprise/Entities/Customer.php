<?php

namespace Core\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Entities\Traits\EntityTrait;
use Core\Domain\Enterprise\ValueObjects\Uuid;
use Core\Domain\Enterprise\Validation\EntityValidation;
use Core\Domain\Enterprise\ValueObjects\CpfCnpj;
use Core\Domain\Enterprise\Dtos\CustomerDto;
use Datetime;

class Customer
{
    use EntityTrait;

    protected $id;
    protected $externalId;
    protected $name;
    protected $cpfCnpj;
    protected $createdAt;

    public function __construct(CustomerDto $customerData)
    {
        $this->id = $customerData->id ? new Uuid($customerData->id) : Uuid::random();
        $this->externalId = $customerData->externalId;
        $this->name = $customerData->name;
        $this->cpfCnpj = new CpfCnpj($customerData->cpfCnpj);
        $this->createdAt = $customerData->createdAt ? new DateTime($customerData->createdAt) : new DateTime();

        $this->validate();
    }

    public function getData()
    {
        return [
            'id' => (string)$this->id,
            'externalId' => $this->externalId,
            'name' => $this->name,
            'cpfCnpj' => (string)$this->cpfCnpj,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }

    public function update(?string $name)
    {
        $this->name = $name;

        $this->validate();
    }

    protected function validate()
    {
        EntityValidation::notNull($this->name);
        EntityValidation::strMaxLength($this->name, 255);
        EntityValidation::strMinLength($this->name, 3);
    }
}
