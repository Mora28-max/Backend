<?php

namespace App\Helpers;

class FormatStrings
{
    public static function normalizeString($string)
    {
        $string = strtolower($string);
        $string = strtr($string, [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'ü' => 'u',
            'ñ' => 'n',
            'Á' => 'a',
            'É' => 'e',
            'Í' => 'i',
            'Ó' => 'o',
            'Ú' => 'u',
            'Ü' => 'u',
            'Ñ' => 'n',
        ]);
        $string = preg_replace('/[^a-z0-9]/', '', $string);
        return $string;
    }
}
