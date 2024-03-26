<?php

namespace Core\Domain\Enterprise\Validation;

use Core\Domain\Enterprise\Exceptions\EntityValidationException;

class EntityValidation
{
    public static function notNull(string $value, ?string $exceptMessage = null)
    {
        if (empty($value)) {
            throw new EntityValidationException($exceptMessage ?? 'Should not be empty or null');
        }
    }

    public static function strMaxLength(string $value, int $length = 255, ?string $exceptMessage = null)
    {
        if (strlen($value) >= $length) {
            throw new EntityValidationException($exceptMessage ?? "The value must not be greater than {$length} characters");
        }
    }

    public static function strMinLength(string $value, int $length = 3, ?string $exceptMessage = null)
    {
        if (strlen($value) < $length) {
            throw new EntityValidationException($exceptMessage ?? "The value must be at least {$length} characters");
        }
    }
}
