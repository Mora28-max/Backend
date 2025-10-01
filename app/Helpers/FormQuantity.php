<?php

namespace App\Helpers;

use NumberFormatter;
use NumberToWords\NumberToWords;


class FormQuantity
{
    public static function convertFloatToText(float $value): string
    {
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('es');
        $parts = explode('.', number_format($value, 2, '.', ''));
        $value_int = (int) $parts[0];
        $value_dec = (int) $parts[1];
        $value_int_words = $numberTransformer->toWords($value_int);
        $value_dec_words = $numberTransformer->toWords($value_dec);
        $value_words = $value_int_words . ' pesos con ' . $value_dec_words . ' centavos';

        return $value_words;
    }

    public static function formatCurrency(string|float $value): string
    {
        return '$' . number_format((float)$value, 2, '.', ',');
    }
}
