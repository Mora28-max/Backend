<?php

namespace App\Services;

use App\Models\Customers\Customer;
use App\Models\Catalogs\FixedServiceRates;
use App\Models\Catalogs\MeasuredServiceRates;

class ServiceRateResolver
{
    public static function resolveServiceRate(Customer $customer, int $year): float
    {
        $use_of_type_id = $customer->use_of_type_id;
        $classification_type_id = $customer->classification_type_id;
        $data = [
            'use_of_type_id' => $use_of_type_id,
            'classification_type_id' => $classification_type_id,
            'year' => $year,
        ];

        switch ((string)$customer->service_type_id) {
            case '1':
                return self::resolveMeasuredServiceRate($data, FixedServiceRates::class);
            case '2':
                return self::resolveMeasuredServiceRate($data, MeasuredServiceRates::class);
            default:
                return 0;
                break;
        }
    }

    public static function resolveMeasuredServiceRate(array $data, $model): float
    {
        $rate = $model::where('use_of_type_id', $data['use_of_type_id'])
            ->where('classification_type_id', $data['classification_type_id'])
            ->where('year', $data['year'])
            ->first();

        return $rate->amount ?? 0;
    }
}
