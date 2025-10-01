<?php

namespace App\Services\Audit;

use Carbon\Carbon;
use App\Models\Payments\Payment;
use App\Models\Catalogs\PaymentType;
use App\Models\Audit\CashRegisterAudit;
use App\Models\Payments\RemainingPayment;
use App\Models\Payments\AdditionalPayment;

class CashRegisterAuditService
{
    public function createAudit(array $data): void
    {
        $system_cash_payments = Payment::where('payment_type_id', 1)
            ->whereBetween('payment_date', [$data['started_at'], $data['ended_at']])
            ->where('canceled', false)
            ->get();
        $system_cash_remaining_payments = RemainingPayment::where('payment_type_id', 1)
            ->whereBetween('payment_date', [$data['started_at'], $data['ended_at']])
            ->where('canceled', false)
            ->get();
        $system_additional_payments = AdditionalPayment::where('payment_type_id', 1)
            ->whereBetween('created_at', [$data['started_at'], $data['ended_at']])
            ->where('canceled', false)
            ->get();

        $digital_system_cash_payments = Payment::whereNot('payment_type_id', 1)
            ->whereBetween('payment_date', [$data['started_at'], $data['ended_at']])
            ->where('canceled', false)
            ->get();

        $digital_system_cash_remaining_payments = RemainingPayment::whereNot('payment_type_id', 1)
            ->whereBetween('payment_date', [$data['started_at'], $data['ended_at']])
            ->where('canceled', false)
            ->get();

        $digital_system_cash_additional_payments = AdditionalPayment::whereNot('payment_type_id', 1)
            ->whereBetween('created_at', [$data['started_at'], $data['ended_at']])
            ->where('canceled', false)
            ->get();

        $digital_types = [2, 3, 5, 6];

        $digital_details = [];
        foreach ($digital_types as $type) {
            $digital_details[$type] =
                ($digital_system_cash_payments->where('payment_type_id', $type)->sum('total'))
                + ($digital_system_cash_remaining_payments->where('payment_type_id', $type)->sum('total'))
                + ($digital_system_cash_additional_payments->where('payment_type_id', $type)->sum('total'));
        }
        $digital_details_json = json_encode($digital_details);

        $system_general_total = $system_cash_payments->sum('total') + $system_cash_remaining_payments->sum('total') + $system_additional_payments->sum('total');
        $digital_general_total = $digital_system_cash_payments->sum('total') + $digital_system_cash_remaining_payments->sum('total') + $digital_system_cash_additional_payments->sum('total');
        $difference = round($system_general_total, 2) - round($data['counted_cash'], 2);

        $json_details = json_decode($data['details'], true);
        $json_sum = 0;
        foreach ($json_details as $detail) {
            $key = array_key_first($detail);
            $json_sum += ($detail[$key] * $key);
        }

        if ($data['counted_cash'] !== $json_sum) throw new \Exception('La suma del desglose no coincide con el total contado');
        if ($difference < 0) throw new \Exception('El total contado es mayor que el total general');
        if ($difference > 0) throw new \Exception('El total contado es menor que el total general');

        CashRegisterAudit::create([
            'user_id' => auth()->id(),
            'receiver_user_id' => $data['receiver_user_id'],
            'witness_user_id' => $data['witness_user_id'],
            'counted_cash' => $data['counted_cash'],
            'system_cash' => $system_general_total,
            'discrepancy' => $difference,
            'notes' => $data['notes'] ?? null,
            'started_at' => $data['started_at'],
            'ended_at' => $data['ended_at'],
            'details' => $data['details'],
            'digital_total' => $digital_general_total,
            'digital_details' => $digital_details_json,
        ]);
    }

    public function getTotalAmountByDateRange(string $start_date, string $end_date)
    {
        $system_cash_payments = Payment::where('payment_type_id', 1)
            ->whereBetween('payment_date', [$start_date, $end_date])
            ->where('canceled', false)
            ->get();
        $system_cash_remaining_payments = RemainingPayment::where('payment_type_id', 1)
            ->whereBetween('payment_date', [$start_date, $end_date])
            ->where('canceled', false)
            ->get();
        $system_additional_payments = AdditionalPayment::where('payment_type_id', 1)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('canceled', false)
            ->get();

        $digital_system_cash_payments = Payment::whereNot('payment_type_id', 1)
            ->whereBetween('payment_date', [$start_date, $end_date])
            ->where('canceled', false)
            ->get();

        $digital_system_cash_remaining_payments = RemainingPayment::whereNot('payment_type_id', 1)
            ->whereBetween('payment_date', [$start_date, $end_date])
            ->where('canceled', false)
            ->get();

        $digital_system_cash_additional_payments = AdditionalPayment::whereNot('payment_type_id', 1)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('canceled', false)
            ->get();

        $digital_types = [2, 3, 5, 6];

        $digital_details = [];
        foreach ($digital_types as $type) {
            $digital_details[$type] =
                ($digital_system_cash_payments->where('payment_type_id', $type)->sum('total'))
                + ($digital_system_cash_remaining_payments->where('payment_type_id', $type)->sum('total'))
                + ($digital_system_cash_additional_payments->where('payment_type_id', $type)->sum('total'));
        }

        $payment_types = PaymentType::pluck('name', 'id')->toArray();

        foreach ($digital_details as $key => $total) {
            $type_name = $payment_types[$key] ?? "Tipo " . $key;
            $digital_details[$type_name] = $total;
            unset($digital_details[$key]);
        }

        return [
            'system_cash' => $system_cash_payments->sum('total'),
            'system_cash_remaining' => $system_cash_remaining_payments->sum('total'),
            'system_additional' => $system_additional_payments->sum('total'),
            'digital_details' => $digital_details,
        ];
    }
}
