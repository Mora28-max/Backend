<?php

namespace App\Services\Payment;

use App\Helpers\GenerateTrackingFolio;
use App\Models\Payments\AdditionalPayment;

class AdditionalPaymentService
{
    public function create(array $data): AdditionalPayment
    {
        $data['total'] = $data['subtotal'] + $data['vat'];
        $data['payment_folio'] = GenerateTrackingFolio::generatePaymentFolio();
        $data['user_id'] = auth()->id();
        return AdditionalPayment::create($data);
    }

    public function update(AdditionalPayment $additional_payment, array $data): AdditionalPayment
    {
        $additional_payment->update($data);
        return $additional_payment;
    }

    public function cancel(AdditionalPayment $additional_payment): void
    {
        if ($additional_payment->canceled)
            throw new \Exception('El pago adicional ya ha sido cancelado.');

        $additional_payment->canceled = true;
        $additional_payment->save();
    }
}
