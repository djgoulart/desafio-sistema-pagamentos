<?php

namespace Core\Domain\Enterprise\Entities;

use Core\Domain\Enterprise\Entities\Traits\EntityTrait;
use Core\Domain\Enterprise\ValueObjects\Uuid;
use Core\Domain\Enterprise\Validation\EntityValidation;
use Datetime;

class Customer
{
    use EntityTrait;

    public function __construct(
        protected Uuid|string $id = '',
        protected string $externalId = '',
        protected string $name = '',
        protected string $cpfCnpj = '',
        protected Datetime|string $createdAt = '',
    ) {

        $this->id = $this->id ? new Uuid($this->id) : Uuid::random();
        $this->createdAt = $this->createdAt ? new DateTime($this->createdAt) : new DateTime();

        $this->validate();
    }

    public function update(string $name)
    {
        $this->name = $name;

        $this->validate();
    }

    protected function validate()
    {
        EntityValidation::notNull($this->name);
        EntityValidation::strMaxLength($this->name);
        EntityValidation::strMinLength($this->name);
        EntityValidation::notNull($this->cpfCnpj);
        EntityValidation::strMaxLength($this->cpfCnpj, 14);
        EntityValidation::strMinLength($this->cpfCnpj, 11);
    }
}
