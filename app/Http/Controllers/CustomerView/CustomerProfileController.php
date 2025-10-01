<?php

namespace App\Http\Controllers\CustomerView;

use App\Models\Reports\Report;
use App\Models\Payments\Payment;
use App\Models\Customers\Customer;
use App\Traits\HasReportRelations;
use App\Http\Controllers\Controller;
use App\Traits\HasCustomerRelations;
use App\Services\Payment\PaymentService;
use App\Models\Payments\RemainingPayment;
use App\Models\Payments\MonthlyServiceCharge;
use App\Http\Resources\Payment\PaymentResource;
use App\Http\Resources\Report\ReportCollection;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Payment\PaymentCollection;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Http\Resources\Payment\RemainingPaymentCollection;
use App\Http\Resources\CustomerView\WaterReceiptCollection;

class CustomerProfileController extends Controller
{
    //
    use HasCustomerRelations, HasReportRelations;

    public function __construct(
        private PaymentService $payment_service,
        protected ReportRepositoryInterface $report_repository
    ) {}

    public function getCustomerData(Customer $customer)
    {
        $customer->load($this->customerRelations());
        return new CustomerResource($customer);
    }

    public function getStatistics(int $id)
    {
        $report_counts = Report::where('customer_id', $id)
            ->selectRaw('COUNT(*) as total_reports, SUM(process_status_id IN (1,2)) as total_reports_in_progress')
            ->first();

        $monthly_aggregates = MonthlyServiceCharge::where('customer_id', $id)
            ->where('monthly_payment_statement_id', 2)
            ->selectRaw('SUM(total_amount) as total_debt, SUM(water_surcharge) as water_surcharge, SUM(drainage_surcharge) as drainage_surcharge')
            ->first();

        $total_debt = (float) ($monthly_aggregates->total_debt ?? 0);
        $total_surcharge = (float) (($monthly_aggregates->water_surcharge ?? 0) + ($monthly_aggregates->drainage_surcharge ?? 0));

        return response([
            'reports' => [
                'total_reports' => (int) ($report_counts->total_reports ?? 0),
                'total_reports_in_progress' => (int) ($report_counts->total_reports_in_progress ?? 0),
            ],
            'debt' => [
                'total_debt' => $total_debt,
                'total_surcharge' => $total_surcharge,
            ],
        ]);
    }

    public function getReports(int $id)
    {
        $reports = $this->report_repository->getReportsByCustomerId($id, 10);
        return new ReportCollection($reports);
    }

    public function getPayments(int $customer_id)
    {
        $payments = Payment::where('customer_id', $customer_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        foreach ($payments as $payment) {
            $charges = MonthlyServiceCharge::where('folio', $payment->folio)
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            $first = $charges->first();
            $last = $charges->last();

            $payment->period = [
                'start' => $first ? ['month' => $first->month, 'year' => $first->year] : null,
                'end' => $last ? ['month' => $last->month, 'year' => $last->year] : null,
            ];
        }


        return new PaymentCollection($payments);
    }

    public function getRemainingPayments(int $customer_id)
    {
        $remaining_payments = RemainingPayment::where('customer_id', $customer_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return new RemainingPaymentCollection($remaining_payments);
    }

    public function getWaterReceipts(int $customer_id)
    {
        $water_receipts = MonthlyServiceCharge::where('customer_id', $customer_id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);
        return new WaterReceiptCollection($water_receipts);
    }

    public function getPayment(int|string $folio)
    {
        $payment = Payment::where('folio', $folio)->firstOrFail();
        $data = $this->payment_service->getPayment($payment);
        return new PaymentResource($data);
    }
}
