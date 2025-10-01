<?php

namespace App\Services\MonthlyAmountsMutation;

use App\Models\Customers\Customer;
use App\Models\Payments\MonthlyServiceCharge;
use App\Services\MonthlyAmountsMutation\MonthlyChargeService;
use Illuminate\Database\Eloquent\Collection;

class AddMonthlyAmountService extends MonthlyChargeService
{
    protected $vat_value = 0.16;

    public function createChargesForRemainingMonths(Customer $customer, $month = null, $year = null): void
    {
        $current_month = $month ?? (int) date('m');
        $current_year = $year ?? (int) date('Y');

        if ($customer->in_agreement)
            throw new \Exception('El usuario se encuentra en convenio. No se pueden generar recargos.');

        for ($current_month; $current_month <= 12; $current_month++) {
            $this->createMonthlyCharge($customer, $current_month, $current_year);
        }
    }

    public function createMonthlyCharge(Customer $customer, int $month, int $year): MonthlyServiceCharge
    {
        $calculated_charge = $this->getNewMonthlyCharge($customer, $year);

        return MonthlyServiceCharge::create([
            'customer_id' => $customer->id,
            'has_drainage' => $customer->drainage_use,
            'year' => $year,
            'month' => $month,
            'water_amount' => $calculated_charge['water_amount'],
            'drainage_amount' => $calculated_charge['drainage_amount'],
            'overdue_months' => 0,
            'water_surcharge' => 0,
            'water_surcharge_discount' => 0,
            'drainage_surcharge' => 0,
            'drainage_surcharge_discount' => 0,
            'water_discount' => $calculated_charge['water_discount'],
            'drainage_discount' => 0,
            'water_amount_subtotal' => $calculated_charge['water_amount_subtotal'],
            'drainage_amount_subtotal' => $calculated_charge['drainage_amount_subtotal'],
            'vat' => $calculated_charge['vat'],
            'total_amount' => $calculated_charge['total_amount'],
            'monthly_payment_statement_id' => 2,
        ]);
    }
}
