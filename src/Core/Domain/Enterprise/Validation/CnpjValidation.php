<?php

namespace Core\Domain\Enterprise\Validation;

class CnpjValidation
{
    public static function validate(?string $cnpj = ''): bool
    {
        if (! $cnpj || empty($cnpj)) {
            return false;
        }

        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) != 14) {
            return false;
        }

        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        // Cálculo dos dígitos verificadores
        for ($t = 12; $t < 14; $t++) {
            $d = 0;
            $c = 0;
            $pos = $t - 7;
            for ($i = $t; $i >= 1; $i--) {
                $d += $cnpj[$c] * $pos--;
                if ($pos < 2) {
                    $pos = 9;
                }
                $c++;
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}
