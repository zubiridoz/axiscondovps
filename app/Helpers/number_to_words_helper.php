<?php

/**
 * Convierte un número a su representación en texto en español.
 * Ejemplo: 5000.00 → "Cinco mil pesos 00/100 M.N."
 */
if (!function_exists('number_to_words_es')) {

    function number_to_words_es(float $amount): string
    {
        $intPart  = (int) floor(abs($amount));
        $decPart  = (int) round((abs($amount) - $intPart) * 100);

        $words = _convertNumber($intPart);
        $words = ucfirst($words);

        $decStr = str_pad($decPart, 2, '0', STR_PAD_LEFT);

        return "{$words} pesos {$decStr}/100 M.N.";
    }

    function _convertNumber(int $n): string
    {
        $units = ['', 'un', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
        $teens = ['diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve'];
        $tens  = ['', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
        $hundreds = ['', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];

        if ($n === 0) return 'cero';
        if ($n === 100) return 'cien';

        $result = '';

        if ($n >= 1000000) {
            $millions = (int) floor($n / 1000000);
            if ($millions === 1) {
                $result .= 'un millón ';
            } else {
                $result .= _convertNumber($millions) . ' millones ';
            }
            $n %= 1000000;
        }

        if ($n >= 1000) {
            $thousands = (int) floor($n / 1000);
            if ($thousands === 1) {
                $result .= 'mil ';
            } else {
                $result .= _convertNumber($thousands) . ' mil ';
            }
            $n %= 1000;
        }

        if ($n >= 100) {
            if ($n === 100) {
                $result .= 'cien';
                return trim($result);
            }
            $result .= $hundreds[(int) floor($n / 100)] . ' ';
            $n %= 100;
        }

        if ($n >= 10 && $n <= 19) {
            $result .= $teens[$n - 10];
            return trim($result);
        }

        if ($n >= 20 && $n <= 29 && $n !== 20) {
            $result .= 'veinti' . $units[$n - 20];
            return trim($result);
        }

        if ($n >= 10) {
            $result .= $tens[(int) floor($n / 10)];
            $n %= 10;
            if ($n > 0) {
                $result .= ' y ' . $units[$n];
            }
            return trim($result);
        }

        if ($n > 0) {
            $result .= $units[$n];
        }

        return trim($result);
    }
}
