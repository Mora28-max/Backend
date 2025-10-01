<?php

namespace App\Services\Customer;

use Carbon\Carbon;
use App\Models\Payments\Payment;
use App\Models\Customers\Customer;
use App\Helpers\GenerateTrackingFolio;
use App\Models\Payments\MonthlyPaymentStatement;
use App\Models\Payments\MonthlyServiceCharge;
use App\Services\MonthlyAmountsMutation\AddMonthlyAmountService;
use App\Services\MonthlyAmountsMutation\UpdateMonthlyAmountService;
use App\Services\MonthlyAmountsMutation\OperationChangeAmountService;

class CustomerChargeUpdatesService
{
    public function __construct(
        protected AddMonthlyAmountService $add_monthly_amount_service,
        protected UpdateMonthlyAmountService $update_monthly_amount_service,
        protected OperationChangeAmountService $operation_change_amount_service,
    ) {}

    public function updateChargerWithFolio(array $id_months_to_pay, string $customer_id): string
    {
        $new_folio = GenerateTrackingFolio::generatePaymentFolio();

        $customer = Customer::find($customer_id);
        if (!$customer)
            throw new \Exception("El usuario no existe.");

        $service_type_id = $customer->service_type_id;

        foreach ($id_months_to_pay as $month_id) {
            $charge = MonthlyServiceCharge::find($month_id);
            if (!$charge)
                throw new \Exception("Alguno de los meses seleccionados no existe");
            if ((string)$charge->customer_id !== (string)$customer_id)
                throw new \Exception("El usuario seleccionado no es el mismo del cobro");
            if ((string)$service_type_id === '2' && !$charge->new_reading)
                throw new \Exception("No se puede pagar el cobro sin lecturas");

            $is_paid = $charge->folio;
            $date_formated = Carbon::parse($charge->year . '-' . $charge->month . '-08');

            if ($is_paid)
                throw new \Exception("El mes $date_formated ya ha sido pagado");

            $charge->folio = $new_folio;
            $charge->monthly_payment_statement_id = 1;
            $charge->save();
        }

        $monthly_payments = MonthlyServiceCharge::where('customer_id', $customer_id)
            ->where('monthly_payment_statement_id', 2)
            ->get();

        $this->operation_change_amount_service->updateAmountWithSurcharge($monthly_payments);
        return $new_folio;
    }

    public function removeFolioAndReset(Payment $payment): void
    {
        $months_paid = MonthlyServiceCharge::where('folio', $payment->folio)->get();
        foreach ($months_paid as $month_paid) {
            $month_paid->folio = null;
            $month_paid->monthly_payment_statement_id = 2;
            $month_paid->save();
        }
    }

    public function cancelCharges(Customer $customer): void
    {
        $months_paid = MonthlyServiceCharge::where('customer_id', $customer->id)->get();

        if (!$months_paid->isNotEmpty())
            throw new \Exception("No hay meses disponibles para cancelar.");

        $monthly_payment_statements = MonthlyPaymentStatement::select('id', 'name')->get();
        $months_paid->each(function ($month_paid) use ($monthly_payment_statements) {

            $monthly_payment_statement = $monthly_payment_statements->firstWhere('id', $month_paid->monthly_payment_statement_id);
            if ($monthly_payment_statement && $monthly_payment_statement->name !== "Pagado") {
                $month_paid->monthly_payment_statement_id = 6;
                $month_paid->save();
            }
        });
    }
}
