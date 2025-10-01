<?php

namespace App\Helpers;

use Carbon\Carbon;

class FormatDate
{

    public static function month(int $month): string
    {
        Carbon::setLocale('es');
        $date = Carbon::createFromDate(null, $month, 1);
        $monthName = $date->translatedFormat('F');
        $monthName = ucfirst($monthName);

        return $monthName;
    }

    public static function fullDateTime(string $dateTime): string
    {
        Carbon::setLocale('es');
        try {
            $carbonDate = Carbon::parse($dateTime);
            return $carbonDate->translatedFormat('l, d \d\e F \d\e Y');
        } catch (\Exception $e) {
            return (string) $dateTime;
        }
    }

    public static function longDate(string $dateTime): string
    {
        Carbon::setLocale('es');
        try {
            $carbonDate = Carbon::parse($dateTime);
            // Example. VEINTE Y OCHO DIAS, DEL MES DE AGOSTO DE 2025
            return $carbonDate->translatedFormat('d \D\I\A\S, \D\E\L \M\E\S \D\E F \D\E Y');
        } catch (\Exception $e) {
            return (string) $dateTime;
        }
    }
}
