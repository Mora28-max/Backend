<?php

namespace App\Services\Payment;

use Throwable;
use Illuminate\Http\Response;
use App\Models\Reports\Report;
use Illuminate\Support\Facades\DB;
use App\Helpers\GenerateTrackingFolio;
use App\Models\Payments\RemainingPayment;
use Carbon\Carbon;

class PayRemainingPaymentService
{

    public function payRemainingPayment(RemainingPayment $remaining_payment, array $data): string
    {
        if ($remaining_payment->canceled)
            throw new \Exception('El adeudo ya ha sido pagado o cancelado.');

        switch ($data['type_remaining']) {
            case 'rep':
                return $this->payRemainingReport($remaining_payment, $data);
                break;
            case 'notif':
                return $this->payRemainingNotification($remaining_payment, $data);
                break;
            case 'extra':
                return $this->payRemainingExtra($remaining_payment, $data);
                break;
            case 'excess':
                return $this->payRemainingExcess($remaining_payment, $data);
                break;
            default:
                throw new \Exception('Tipo de pago restante no válido.');
        }
    }

    protected function payRemainingReport(RemainingPayment $remaining_payment, array $data): string
    {
        DB::transaction(function () use ($remaining_payment, $data) {
            $new_folio = GenerateTrackingFolio::generatePaymentFolio();

            $remaining_payment->update([
                'payment_folio' => $new_folio,
                'payment_type_id' => $data['payment_type_id'],
                'payment_date' => Carbon::now()->toDateTimeString(),
                'monthly_payment_statement_id' => 1,
                'note' => $data['note'] ?? '',
            ]);

            $report = Report::where('tracking_folio', $remaining_payment->tracking_folio)->first();

            if (!$report)
                throw new \Exception('No se encontró el reporte asociado al adeudo.');

            $report->update([
                'payment_folio' => $new_folio,
                'should_be_paid' => true,
            ]);
        });
        return "Pago de reporte exitoso";
    }

    protected function payRemainingExtra(RemainingPayment $remaining_payment, array $data): string
    {

        DB::transaction(function () use ($remaining_payment, $data) {
            $new_folio = GenerateTrackingFolio::generatePaymentFolio();

            $remaining_payment->update([
                'payment_folio' => $new_folio,
                'payment_type_id' => $data['payment_type_id'],
                'payment_date' => Carbon::now()->toDateTimeString(),
                'monthly_payment_statement_id' => 1,
                'note' => $data['note'] ?? '',
            ]);
        });

        return "Pago de adicional exitoso";
    }

    protected function payRemainingExcess(RemainingPayment $remaining_payment, array $data): string
    {
        DB::transaction(function () use ($remaining_payment, $data) {
            $new_folio = GenerateTrackingFolio::generatePaymentFolio();
            if (!empty($data['vat']))
                throw new \Exception('El adeudo excesivo no se le puede aplicar IVA');
            if (!empty($data['discount']))
                throw new \Exception('El adeudo excesivo no se le puede aplicar descuento');

            $remaining_payment->update([
                'payment_folio' => $new_folio,
                'payment_type_id' => $data['payment_type_id'],
                'payment_date' => Carbon::now()->toDateTimeString(),
                'monthly_payment_statement_id' => 1,
                'note' => $data['note'] ?? '',
            ]);
        });
        return "Pago de exceso exitoso";
    }

    protected function payRemainingNotification(RemainingPayment $remaining_payment, array $data): string
    {
        DB::transaction(function () use ($remaining_payment, $data) {
            $new_folio = GenerateTrackingFolio::generatePaymentFolio();
            $remaining_payment->update([
                'payment_folio' => $new_folio,
                'payment_type_id' => $data['payment_type_id'],
                'payment_date' => Carbon::now()->toDateTimeString(),
                'monthly_payment_statement_id' => 1,
                'note' => $data['note'] ?? '',
            ]);
        });

        return "Pago de notificación exitoso";
    }
}
