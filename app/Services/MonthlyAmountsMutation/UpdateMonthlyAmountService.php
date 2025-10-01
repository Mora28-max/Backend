<?php

namespace App\Services\MonthlyAmountsMutation;

use App\Models\Customers\Customer;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Payments\MonthlyServiceCharge;
use App\Services\Payment\RemainingPaymentService;
use App\Services\MonthlyAmountsMutation\MonthlyChargeService;

class UpdateMonthlyAmountService extends MonthlyChargeService
{
    public function __construct(protected RemainingPaymentService $remaining_payment_service) {}

    public function updateMontlyCharge(Collection $months_to_update, Customer $customer): void
    {
        foreach ($months_to_update as $month_paid) {
            $calculated_charge = $this->getNewMonthlyCharge($customer, $month_paid['year']);
            $month_paid->update([
                'water_amount' => $calculated_charge['water_amount'],
                'drainage_amount' => $calculated_charge['drainage_amount'],
                'overdue_months' => $calculated_charge['overdue_months'],
                'water_surcharge' => $calculated_charge['water_surcharge'],
                'water_surcharge_discount' => $calculated_charge['water_surcharge_discount'],
                'drainage_surcharge' => $calculated_charge['drainage_surcharge'],
                'drainage_surcharge_discount' => $calculated_charge['drainage_surcharge_discount'],
                'water_discount' => $calculated_charge['water_discount'],
                'drainage_discount' => $calculated_charge['drainage_discount'],
                'water_amount_subtotal' => $calculated_charge['water_amount_subtotal'],
                'drainage_amount_subtotal' => $calculated_charge['drainage_amount_subtotal'],
                'vat' => $calculated_charge['vat'],
                'total_amount' => $calculated_charge['total_amount'],
            ]);
        }

        $unique_folios = $months_to_update->pluck('folio')->unique()->filter()->values();
        if (!$unique_folios->isEmpty()) {
            $this->remaining_payment_service->createRemainingPayment($unique_folios->toArray(), $customer->id);
        }
    }

    public function updateMontlySurcharge(Collection $months_to_update, Customer $customer): void
    {
        foreach ($months_to_update as $month) {
            $calculated_charge = $this->getNewMonthlyCharge($customer, $month['year']);
            $month->update([
                'water_amount' => $calculated_charge['water_amount'],
                'drainage_amount' => $calculated_charge['drainage_amount'],
                'overdue_months' => $calculated_charge['overdue_months'],
                'water_surcharge' => $calculated_charge['water_surcharge'],
                'water_surcharge_discount' => $calculated_charge['water_surcharge_discount'],
                'drainage_surcharge' => $calculated_charge['drainage_surcharge'],
                'drainage_surcharge_discount' => $calculated_charge['drainage_surcharge_discount'],
                'water_discount' => $calculated_charge['water_discount'],
                'drainage_discount' => $calculated_charge['drainage_discount'],
                'water_amount_subtotal' => $calculated_charge['water_amount_subtotal'],
                'drainage_amount_subtotal' => $calculated_charge['drainage_amount_subtotal'],
                'vat' => $calculated_charge['vat'],
                'total_amount' => $calculated_charge['total_amount'],
            ]);
        }
    }

    public function updateCharges(Customer $customer): void
    {
        $months_toBe_updated = MonthlyServiceCharge::where('customer_id', $customer->id)
            ->where('year', date('Y'))
            ->where('month', '>=', date('m'))
            ->get();

        if ($months_toBe_updated->isEmpty())
            throw new \Exception('No hay meses asociados a este usuario');

        $this->updateMontlyCharge($months_toBe_updated, $customer);
    }
}
