<?php

namespace App\Helpers;

class GeoHelper
{
    public static function getLatLngFromAddress(?string $address): ?array
    {
        if (is_null($address)) return null;

        $fullAddress = $address . ', Zacapoaxtla, Puebla, 73680';
        $encodedAddress = urlencode($fullAddress);
        $url = "https://nominatim.openstreetmap.org/search?q={$encodedAddress}&format=json&addressdetails=1&limit=1";

        $opts = [
            "http" => [
                "header" => "User-Agent: LaravelApp/1.0 (your-email@example.com)\r\n"
            ]
        ];
        $context = stream_context_create($opts);

        $result = @file_get_contents($url, false, $context);

        if ($result === false) {
            return null;
        }

        $data = json_decode($result, true);

        if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
            return [
                'lat' => (float) $data[0]['lat'],
                'lng' => (float) $data[0]['lon']
            ];
        }

        return null;
    }
}
