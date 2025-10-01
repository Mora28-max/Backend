<?php

namespace App\Services\Payment;

use Carbon\Carbon;
use App\Models\Catalogs\ProcessStatus;
use App\Models\Customers\Customer;
use App\Models\Notice\Notice;
use App\Models\Payments\Payment;
use Illuminate\Support\Facades\DB;
use App\Models\Pivots\NoticeReport;
use Illuminate\Support\Facades\Auth;
use App\Models\Payments\MonthlyServiceCharge;
use App\Models\Reports\Report;
use App\Services\Customer\CustomerChargeUpdatesService;


class PaymentService
{

    public function __construct(protected CustomerChargeUpdatesService $charge_service_updates) {}

    public function createPayment(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $this->editNoticeStatus($data['customer_id']);
            $this->finishNoticeReport($data['customer_id']);
            $folio = $this->charge_service_updates->updateChargerWithFolio($data['id_months_to_pay'], $data['customer_id']);
            $months_paid = MonthlyServiceCharge::where('folio', $folio)->get();
            $payment = Payment::create([
                'customer_id' => $data['customer_id'],
                'user_id' => Auth::user()->id,
                'folio' => $folio,
                'total' => $months_paid->sum('total_amount'),
                'payment_type_id' => $data['payment_type_id'],
                'payment_date' => Carbon::now()->toDateTimeString(),
                'notes' => $data['notes'] ?? '',
            ]);

            return $payment;
        });
    }

    public function getPayment(Payment $payment): Payment
    {
        $charges = MonthlyServiceCharge::where('folio', $payment->folio)
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $first = $charges->first();
        $last = $charges->last();

        $water = $charges->sum('water_amount') +  $charges->sum('excess_water_amount');
        $water_surcharge = $charges->sum('water_surcharge') +  $charges->sum('excess_water_surcharge');
        $water_discount = $charges->sum('water_discount') + $charges->sum('excess_water_discount');
        $water_surcharge_discount = $charges->sum('water_surcharge_discount') + $charges->sum('excess_water_surcharge_discount');

        $drainage = $charges->sum('drainage_amount') + $charges->sum('excess_drainage_amount');
        $drainage_surcharge = $charges->sum('drainage_surcharge') + $charges->sum('excess_drainage_surcharge');
        $drainage_discount = $charges->sum('drainage_discount') + $charges->sum('excess_drainage_discount');
        $drainage_surcharge_discount = $charges->sum('drainage_surcharge_discount') + $charges->sum('excess_drainage_surcharge_discount');

        $vat = $charges->sum('vat');

        $payment->water = $water;
        $payment->water_surcharge = $water_surcharge;
        $payment->water_discount = $water_discount;
        $payment->drainage = $drainage;
        $payment->drainage_surcharge = $drainage_surcharge;
        $payment->drainage_discount = $drainage_discount;
        $payment->vat = $vat;
        $payment->water_surcharge_discount = $water_surcharge_discount;
        $payment->drainage_surcharge_discount = $drainage_surcharge_discount;

        $payment->period = [
            'start' => $first ? ['month' => $first->month, 'year' => $first->year] : null,
            'end' => $last ? ['month' => $last->month, 'year' => $last->year] : null,
        ];

        return $payment;
    }

    public function updatePayment(Payment $payment, array $data): Payment
    {
        if ($payment->canceled)
            throw new \Exception('El cobro está cancelado.');
        $payment->update($data);
        return $payment;
    }

    public function cancelPayment(Payment $payment): void
    {
        if ($payment->canceled)
            throw new \Exception('El cobro ya ha sido cancelado.');

        DB::transaction(function () use ($payment) {
            $this->charge_service_updates->removeFolioAndReset($payment);
            $payment->canceled = true;
            $payment->save();
        });
    }

    private function editNoticeStatus(int $customer_id): void
    {
        $notice = Notice::where('customer_id', $customer_id)
            ->whereIn('process_status_id', [1, 2, 3])
            ->first();

        if ($notice) {
            $process_status = ProcessStatus::findOrFail($notice->process_status_id);
            if ($process_status->name === 'Cerrado') {
                $notice->process_status_id = 5;
            } else {
                $notice->process_status_id = 4;
            }
            $notice->save();

            $customer = Customer::find($customer_id);
            $customer->is_notified = false;
            $customer->save();
        }
    }

    private function finishNoticeReport(int $customer_id): void
    {
        $notice_report = NoticeReport::where('customer_id', $customer_id)
            ->where('finished_process', false)
            ->first();

        if ($notice_report) {
            $report = Report::findOrFail($notice_report->report_id);

            if (!$notice_report->finished_process) {
                $report->process_status_id = 4;
                $report->save();
            }

            if ($notice_report->finished_process) {
                $report->process_status_id = 5;
                $report->save();
            }

            $notice_report->finished_process = true;
            $notice_report->save();
        }
    }
}
