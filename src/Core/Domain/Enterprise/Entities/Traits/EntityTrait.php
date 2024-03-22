<?php

namespace Core\Domain\Enterprise\Entities\Traits;

trait EntityTrait
{
    public function __get($property)
    {
      if(isset($this->{$property})) {
        return $this->{$property};
      }

      $className = get_class($this);
      throw new \Exception("Property {$property} does not exist in {$className}");
    }

    public function id(): string
    {
      return (string) $this->id;
    }

    public function createdAt(): string
    {
      return $this->createdAt->format('Y-m-d H:i:s');
    }
}
