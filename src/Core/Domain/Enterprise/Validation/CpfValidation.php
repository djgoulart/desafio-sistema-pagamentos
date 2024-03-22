<?php

namespace Core\Domain\Enterprise\Validation;

class CpfValidation
{
    public static function validate(?string $cpf = ''): bool
    {
        if(!$cpf || empty($cpf)) {
            return false;
        }

        $cpf = preg_replace('/\D/', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Cálculo para validar os dois últimos dígitos
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}
