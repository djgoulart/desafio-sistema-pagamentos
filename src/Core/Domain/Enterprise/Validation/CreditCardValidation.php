<?php

namespace Core\Domain\Enterprise\Validation;

use InvalidArgumentException;

class CreditCardValidation
{
    public static function validateHolderName(string $holderName): void
    {
        if (empty($holderName)) {
            throw new InvalidArgumentException('Holder name is required.');
        }

        if (strlen($holderName) < 3) {
            throw new InvalidArgumentException('Holder name must be at least 3 characters long.');
        }
    }

    public static function validateCardNumber(string $number): void
    {
        if (empty($number) || !self::isValidCardNumber($number)) {
            throw new InvalidArgumentException('Invalid card number.');
        }

        $number = preg_replace('/\D/', '', $number);

        if (!self::isValidCardNumber($number)) {
            throw new InvalidArgumentException('Invalid card number.');
        }
    }

    public static function validateExpiryDate(string $expiryMonth, string $expiryYear): void
    {
        if (empty($expiryMonth) || empty($expiryYear) || !self::isExpiryDateValid($expiryMonth, $expiryYear)) {
            throw new InvalidArgumentException('Invalid expiry date.');
        }
    }

    public static function validateCcv(string $ccv): void
    {
        if (empty($ccv) || !self::isValidCcv($ccv)) {
            throw new InvalidArgumentException('Invalid CCV.');
        }
    }


    private static function isValidCardNumber(string $number): bool
    {
        if (!ctype_digit($number)) {
            return false;
        }

        $length = strlen($number);
        if ($length < 13 || $length > 19) {
            return false;
        }

        $sum = 0;
        $numDigits = strlen($number);
        $parity = $numDigits % 2;

        for ($i = 0; $i < $numDigits; $i++) {
            $digit = $number[$i];
            if ($i % 2 == $parity) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }

        return ($sum % 10) == 0;
    }

    private static function isExpiryDateValid(string $month, string $year): bool
    {
        if (!preg_match('/^\d{2}$/', $month)) {
            return false;
        }

        if (!preg_match('/^\d{4}$/', $year)) {
            return false;
        }

        $currentYear = (int)date('Y');
        $currentMonth = (int)date('m');

        $year = (int)$year;
        $month = (int)$month;

        if ($year < $currentYear) {
            return false;
        }

        if ($year === $currentYear && $month <= $currentMonth) {
            return false;
        }

        return $month >= 1 && $month <= 12;
    }

    private static function isValidCcv(string $ccv): bool
    {
        return preg_match('/^\d{3,4}$/', $ccv);
    }
}
