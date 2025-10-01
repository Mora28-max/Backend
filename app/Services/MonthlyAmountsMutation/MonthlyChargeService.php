<?php

namespace App\Services\MonthlyAmountsMutation;

use App\Models\Customers\Customer;
use App\Models\Catalogs\CustomerType;
use App\Services\ServiceRateResolver;
use App\Models\Catalogs\DrainagePercentage;

class MonthlyChargeService
{
    protected $vat_value = 0.16;

    public function getNewMonthlyCharge(Customer $customer, int $year): array
    {
        $drainage_percentage = DrainagePercentage::where('year', $year)->first();
        if (!$drainage_percentage)
            throw new \Exception("No se encontró el porcentaje de drenaje para el año $year");
        $drainage_percentage_value = (float)$drainage_percentage->percentage;

        $water_discount_percentage = CustomerType::where('id', $customer['customer_type_id'])->first();

        if (!$water_discount_percentage)
            throw new \Exception("No se encontró el porcentaje de descuento para el tipo de usuario $customer->customerType->name");

        $water_amount = (float) ServiceRateResolver::resolveServiceRate($customer, $year);

        $has_drainage = (bool)$customer->drainage_use;
        $drainage_amount = (float) ($has_drainage ? $water_amount * $drainage_percentage_value : 0);

        $water_discount = (float) ($water_amount * $water_discount_percentage->percentage_disount);
        $drainage_discount = (float) ($drainage_amount * $water_discount_percentage->percentage_disount);
        $water_amount_subtotal = $water_amount - $water_discount;
        $drainage_amount_subtotal = $drainage_amount - $drainage_discount;
        $subtotal = $water_amount_subtotal + $drainage_amount_subtotal;

        $vat_water = (string) $customer->use_of_type_id !== '1' ? $water_amount_subtotal * $this->vat_value : 0;
        $vat_drainage = $drainage_amount_subtotal * $this->vat_value;
        $vat = $vat_water + $vat_drainage;

        return [
            'water_amount' => $water_amount,
            'drainage_amount' => $drainage_amount,
            'overdue_months' => 0,
            'water_surcharge' => 0,
            'water_surcharge_discount' => 0,
            'drainage_surcharge' => 0,
            'drainage_surcharge_discount' => 0,
            'water_discount' => $water_discount,
            'drainage_discount' => 0,
            'water_amount_subtotal' => $water_amount_subtotal,
            'drainage_amount_subtotal' => $drainage_amount_subtotal,
            'vat' => $vat,
            'total_amount' => $subtotal + $vat,
        ];
    }
}
