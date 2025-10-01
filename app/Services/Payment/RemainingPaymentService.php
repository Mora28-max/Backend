<?php

namespace App\Services\Payment;

use App\Helpers\FormQuantity;
use Illuminate\Http\Response;
use App\Models\Reports\Report;
use App\Models\Payments\Payment;
use Illuminate\Support\Facades\DB;
use App\Helpers\GenerateTrackingFolio;
use App\Models\Payments\RemainingPayment;
use App\Models\Payments\MonthlyServiceCharge;

class RemainingPaymentService
{
    public function createRemainingPayment(array $unique_folios, string $customer_id): void
    {
        //
        $total_difference = self::getDifferencePayment($unique_folios);

        $existing_remaining_payment = RemainingPayment::where('customer_id', $customer_id)
            ->where('monthly_payment_statement_id', 2);

        if ($existing_remaining_payment) {
            $existing_remaining_payment->delete();
        }

        if ($total_difference > 0) {
            RemainingPayment::create([
                'customer_id' => $customer_id,
                'tracking_folio' => GenerateTrackingFolio::generateRemainingPaymentFolio(),
                'total' => $total_difference,
                'note' => 'Este adeudo corresponde a la diferencia de pagos realizados adelantados antes de cambiar la tarifa del servicio.',
                'monthly_payment_statement_id' => 2,
            ]);
        }
    }

    protected static function getDifferencePayment(array $folios): float
    {
        $total_difference = 0;

        foreach ($folios as $folio) {
            $payment = Payment::where('folio', $folio)->first();
            if (!$payment)
                throw new \Exception("No se encontró el pago con el folio $folio");
            $months_paid = MonthlyServiceCharge::where('folio', $payment->folio)->get();

            $total_paid = $months_paid->sum('water_amount_subtotal') + $months_paid->sum('drainage_amount_subtotal');
            $vat = $months_paid->sum('vat');
            $difference = $total_paid + $vat  - (float) $payment->total;
            $total_difference += $difference;
        }

        return (float) $total_difference;
    }

    public function createRemainingPaymentForAdditionals(array $data): RemainingPayment
    {
        return RemainingPayment::create([
            'customer_id' => $data['customer_id'],
            'tracking_folio' => $data['tracking_folio'],
            'subtotal' => $data['subtotal'],
            'vat' => $data['vat'] ?? 0,
            'total' => $data['subtotal'] + ($data['vat'] ?? 0),
            'breakdown' => $data['breakdown'] ?? '',
            'note' => $data['note'] ?? '',
            'monthly_payment_statement_id' => 2,
        ]);
    }

    public function updateRemainingPayment(RemainingPayment $remaining_payment, array $data): RemainingPayment
    {
        if ((string)$remaining_payment->payment_type_id !== (string)$data['payment_type_id']) {
            $remaining_payment->update([
                'payment_type_id' => $data['payment_type_id'],
            ]);
            return $remaining_payment;
        }

        if ($remaining_payment->payment_folio)
            throw new \Exception('El adeudo ya ha sido pagado y no puede ser modificado.');

        $newTotal = ($data['subtotal'] ?? $remaining_payment->subtotal) + ($data['vat'] ?? $remaining_payment->vat) - ($data['discount'] ?? $remaining_payment->discount);
        $remaining_payment->update([
            'subtotal' => $data['subtotal'] ?? $remaining_payment->subtotal,
            'vat' => $data['vat'] ?? $remaining_payment->vat,
            'discount' => $data['discount'] ?? $remaining_payment->discount,
            'payment_type_id' => $data['payment_type_id'] ?? $remaining_payment->payment_type_id,
            'total' => $newTotal,
        ]);
        return $remaining_payment;
    }

    public function cancelRemainingPayment(RemainingPayment $remaining_payment): void
    {
        DB::transaction(function () use ($remaining_payment) {
            $remaining_payment->update(['canceled' => true]);
            $report = Report::where('tracking_folio', $remaining_payment->tracking_folio)->first();
            if ($report) $report->update(['payment_folio' => null]);
        });
    }
}
