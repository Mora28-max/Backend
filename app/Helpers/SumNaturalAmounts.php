<?php


namespace App\Helpers;



use App\Models\Customers\Customer;
use App\Models\Payments\MonthlyServiceCharge;


class SumNaturalAmounts
{

    public static function SumValues(MonthlyServiceCharge $monthlyServiceCharge, float $vat_value): void
    {
        $customer_id = $monthlyServiceCharge->customer_id;

        $customer = Customer::find($customer_id);

        if (!$customer)
            throw new \Exception("La cuenta no existe.");

        $use_of_type = (string) $customer->use_of_type_id === '1';

        $water_subtotal = $monthlyServiceCharge->water_amount
                        + $monthlyServiceCharge->water_surcharge
                        + $monthlyServiceCharge->excess_water_amount
                        + $monthlyServiceCharge->excess_water_surcharge
                        - $monthlyServiceCharge->water_discount
                        - $monthlyServiceCharge->water_surcharge_discount
                        - $monthlyServiceCharge->excess_water_discount
                        - $monthlyServiceCharge->excess_water_surcharge_discount;

        $drainage_subtotal = $monthlyServiceCharge->drainage_amount
                        + $monthlyServiceCharge->drainage_surcharge
                        + $monthlyServiceCharge->excess_drainage_amount
                        + $monthlyServiceCharge->excess_drainage_surcharge
                        - $monthlyServiceCharge->drainage_discount
                        - $monthlyServiceCharge->drainage_surcharge_discount
                        - $monthlyServiceCharge->excess_drainage_discount
                        - $monthlyServiceCharge->excess_drainage_surcharge_discount;

        $water_subtotal_without_surcharge = $monthlyServiceCharge->water_amount
                        + $monthlyServiceCharge->excess_water_amount
                        - $monthlyServiceCharge->water_discount
                        - $monthlyServiceCharge->excess_water_discount;

        $drainage_subtotal_without_surcharge = $monthlyServiceCharge->drainage_amount
                        + $monthlyServiceCharge->excess_drainage_amount
                        - $monthlyServiceCharge->drainage_discount
                        - $monthlyServiceCharge->excess_drainage_discount;

        $water_vat = !$use_of_type ? $water_subtotal_without_surcharge * $vat_value : 0;
        $drainage_vat = $drainage_subtotal_without_surcharge * $vat_value;
        $total_vat = $water_vat + $drainage_vat;

        $subtotal_excess = $monthlyServiceCharge->excess_water_amount
                        + ($monthlyServiceCharge->excess_drainage_amount * $monthlyServiceCharge->excess_water_surcharge)
                        + $monthlyServiceCharge->excess_drainage_surcharge
                        - $monthlyServiceCharge->excess_water_discount
                        - $monthlyServiceCharge->excess_drainage_discount
                        - $monthlyServiceCharge->excess_water_surcharge_discount
                        - $monthlyServiceCharge->excess_drainage_surcharge_discount;

        $total_amount = $water_subtotal + $drainage_subtotal + $total_vat;

        $monthlyServiceCharge->subtotal_excess = $subtotal_excess;
        $monthlyServiceCharge->water_amount_subtotal = $water_subtotal;
        $monthlyServiceCharge->drainage_amount_subtotal = $drainage_subtotal;
        $monthlyServiceCharge->vat = $total_vat;
        $monthlyServiceCharge->total_amount = $total_amount;

        $monthlyServiceCharge->save();
    }
}
