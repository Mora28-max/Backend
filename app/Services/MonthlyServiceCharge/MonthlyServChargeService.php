<?php

namespace App\Services\MonthlyServiceCharge;

use App\Models\Customers\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Payments\MonthlyServiceCharge;
use App\Services\MonthlyAmountsMutation\AddMonthlyAmountService;
use App\Services\MonthlyAmountsMutation\OperationChangeAmountService;

class MonthlyServChargeService
{
    public function __construct(
        protected AddMonthlyAmountService $add_monthly_amount_service,
        protected OperationChangeAmountService $operation_change_amount_service,
    ) {}

    public function storeMonthlyServCharge(array $data): void
    {
        $monthly_payments = MonthlyServiceCharge::where('customer_id', $data['customer_id'])->get();
        if ($monthly_payments->isNotEmpty())
            throw new \Exception('El adedudo ya ha sido registrada.');

        DB::transaction(function () use ($data) {
            $customer = Customer::findOrFail($data['customer_id']);
            $amount_charged = $this->add_monthly_amount_service->createChargesForRemainingMonths($customer);
            return $amount_charged;
        });
    }

    public function updateMonthlyServCharge(array $data, int $customer_id, Collection $monthly_payments): void
    {
        DB::transaction(function () use ($data, $customer_id, $monthly_payments) {
            $this->operation_change_amount_service->updateAmount($customer_id, $data, $monthly_payments);
        });
    }
}
