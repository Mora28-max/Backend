<?php

namespace App\Validators\Agreement;

use Carbon\Carbon;
use App\Models\Customers\Customer;
use App\Models\Payments\MonthlyServiceCharge;

class AgreementValidator
{
    public static function validateCustomerNotAccountCanceled(Customer $customer, string $service_status): void
    {
        if ($service_status === "Cancelado")
            throw new \Exception('El usuario no puede registrar convenio porque se ha cancelado su servicio.');
    }
    public static function validateCustomerNotAccountSuspended(Customer $customer, string $service_status): void
    {
        if ($service_status === "Suspendido")
            throw new \Exception('El usuario no puede registrar convenio porque su servicio está suspendido. Para poder registrar el convenio debe ser reactivado mediante un reporte de reconexión.');
    }
    public static function validateCustomerNotInAgreement(Customer $customer): void
    {
        if ($customer->in_agreement)
            throw new \Exception('El usuario no puede registrar un nuevo convenio porque ya tiene uno activo. Cancele el convenio existente para continuar.');
    }
    public static function validateCustomerNotInNotice(Customer $customer): void
    {
        if ($customer->in_notice)
            throw new \Exception('El usuario no puede registrar convenio porque hay una notificación activa. Por favor cancele la notificación.');
    }

    public static function validatePaymentBreakdown(array $payment_breakdown)
    {
        if (count($payment_breakdown) === 0)
            throw new \Exception('No se ha enviado ningún bloque de pago.');
    }

    public static function serviceTypeAndReadingValidation(string $service_type, $new_reading): void
    {
        if ($service_type === 'medido' && empty($new_reading))
            throw new \Exception("El servicio {$service_type} no puede tener lecturas vacías.");
    }

    public static function validateMonthsToPayAreNotPayment(array $id_months_to_pay)
    {
        if (!empty($id_months_to_pay)) {
            foreach ($id_months_to_pay as $id) {
                $month = MonthlyServiceCharge::findOrFail($id);
                if (!empty($month->folio))  throw new \Exception("El mes con ID {$id} ya ha sido pagado y no puede incluirse en el convenio.");
            }
        }
    }

    public static function isDelayed(string $payment_date, ?int $payment_id): void
    {
        $paymentDate = Carbon::parse($payment_date)->startOfDay();
        $today = Carbon::now()->startOfDay();
        $is_delayed = $today->greaterThan($paymentDate) && empty($payment_id);
        if ($is_delayed)
            throw new \Exception("El pago está atrasado. No se puede continuar con el convenio, se recomienda generar reporte o crear un nuevo convenio.");
    }

    public static function isPaid(array $id_months_to_pay): void
    {
        foreach ($id_months_to_pay as $id) {
            $month = MonthlyServiceCharge::findOrFail($id);
            if (!empty($month->folio))  throw new \Exception("El mes con ID {$id} ya ha sido pagado y no puede pagarse en el convenio.");
        }
    }
    public static function validateStatus(string $process_status): void
    {
        if ($process_status === 'Terminado')
            throw new \Exception("Un convenio terminado no puede generar un reporte.");

        if ($process_status !== 'Cancelado')
            throw new \Exception("El convenio debe estar cancelado para generar el reporte.");
    }
}
