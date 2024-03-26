<?php

namespace Core\Domain\Enterprise\Validation;

use Core\Domain\Enterprise\Exceptions\EntityValidationException;

class PaymentValidation
{
    public static function validateValue($value): void
    {
        if (! is_float($value)) {
            throw new EntityValidationException('The payment value must be of type float.');
        }

        if ($value <= 0) {
            throw new EntityValidationException('The payment value must be greater than zero.');
        }
    }

    public static function validateDueDate($dueDate): void
    {
        if (is_string($dueDate)) {
            $date = \DateTime::createFromFormat('Y-m-d', $dueDate);
            if (! $date || $date->format('Y-m-d') !== $dueDate) {
                throw new EntityValidationException("Invalid due date format. It should be 'year-month-day'.");
            }
        } elseif (! ($dueDate instanceof \DateTime)) {
            throw new EntityValidationException("The due date must be a DateTime object or a string in the 'year-month-day' format.");
        } else {
            $date = $dueDate;
        }

        if ($date < new \DateTime('today')) {
            throw new EntityValidationException('The due date cannot be a past date.');
        }
    }
}
