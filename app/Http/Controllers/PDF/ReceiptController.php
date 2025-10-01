<?php

namespace App\Http\Controllers\PDF;

use Carbon\Carbon;
use App\Helpers\FormatDate;
use App\Helpers\FormQuantity;
use App\Models\Notice\Notice;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payments\Payment;
use App\Models\Customers\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Catalogs\ProcessStatus;
use App\Services\Payment\PaymentService;
use App\Models\Payments\RemainingPayment;
use App\Models\Readings\WaterMeterFolios;
use App\Models\Payments\AdditionalPayment;
use App\Http\Resources\Notice\NoticeResource;
use App\Models\Catalogs\MeasuredServiceRates;
use App\Models\Payments\MonthlyServiceCharge;
use App\Http\Resources\Payment\PaymentResource;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Payment\RemainingPaymentResource;
use App\Http\Resources\Payment\AdditionalPaymentResource;

class ReceiptController extends Controller
{
    public function __construct(private PaymentService $payment_service) {}


    public function getPaymentReceipt(int|string $folio)
    {
        try {
            if (!is_numeric($folio)) throw new \InvalidArgumentException('Folio must be a number');
            $payment = Payment::where('folio', $folio)->first();
            $this->existsPayment($payment);
            $payment = $this->getPaymentData($payment);
            $pdf = Pdf::loadView('pdf.payment-receipt', [
                'payment' => $payment,
            ])->setPaper('letter', 'landscape');
            return $pdf->stream('payment-receipt-' . $payment['folio'] . '.pdf');
        } catch (\Throwable $th) {
            return response(['error' => $th->getMessage()], 400);
        }
    }

    public function getCustomerPaymentReceipt(int|string $folio)
    {
        try {
            if (!is_numeric($folio)) throw new \InvalidArgumentException('Folio must be a number');
            $payment = Payment::where('folio', $folio)->first();
            $this->existsPayment($payment);
            $payment = $this->getPaymentData($payment);
            $pdf = Pdf::loadView('pdf.payment-customer-receipt', [
                'payment' => $payment,
            ])->setPaper('A4', 'portrait');
            return $pdf->stream('payment-receipt-' . $payment['folio'] . '.pdf');
        } catch (\Throwable $th) {
            return response(['error' => $th->getMessage()], 400);
        }
    }

    public function getRemainingPaymentReceipt(int|string $folio)
    {
        try {
            if (!is_numeric($folio)) throw new \InvalidArgumentException('Folio must be a number');
            $payment = RemainingPayment::where('payment_folio', $folio)->first();
            $this->existsPayment($payment);
            $payment = new RemainingPaymentResource($payment);
            $costs = $this->calculateCost($payment['breakdown']);
            $payment = $payment->toArray(request());
            $payment['created_at_text'] = FormatDate::fullDateTime($payment['created_at']);
            $payment['total'] = FormQuantity::formatCurrency((float)$payment['total']);

            $pdf = Pdf::loadView('pdf.remaining-payment-receipt', [
                'payment' => $payment,
                'costs' => $costs,
            ])->setPaper('letter', 'landscape');

            return $pdf->stream('remaining-payment-receipt-' . $payment['payment_folio'] . '.pdf');
        } catch (\Throwable $th) {
            return response(['error' => $th->getMessage()], 400);
        }
    }

    public function getAdditionalPayment(int|string $folio)
    {
        try {
            if (!is_numeric($folio)) throw new \InvalidArgumentException('Folio must be a number');
            $payment = AdditionalPayment::where('payment_folio', $folio)->first();
            $this->existsPayment($payment);
            $payment = new AdditionalPaymentResource($payment);
            $payment = $payment->toArray(request());

            $payment['total_text'] = FormQuantity::convertFloatToText((float)$payment['total']);
            $payment['subtotal'] = FormQuantity::formatCurrency((float)$payment['subtotal']);
            $payment['vat'] = FormQuantity::formatCurrency((float)$payment['vat']);
            $payment['total'] = FormQuantity::formatCurrency((float)$payment['total']);
            $payment['payment_date'] = FormatDate::fullDateTime($payment['created_at']);

            $pdf = Pdf::loadView('pdf.additional-payment-receipt', [
                'payment' => $payment,
            ])->setPaper('letter', 'landscape');
            return $pdf->stream('additional-payment-receipt-' . $payment['payment_folio'] . '.pdf');
        } catch (\Throwable $th) {
            return response(['error' => $th->getMessage()], 400);
        }
    }

    public function getCustomerRemainingPaymentReceipt(int|string $folio)
    {
        try {
            if (!is_numeric($folio)) throw new \InvalidArgumentException('Folio must be a number');
            $payment = RemainingPayment::where('payment_folio', $folio)->first();
            $this->existsPayment($payment);
            $payment = new RemainingPaymentResource($payment);

            $costs = json_decode($payment['breakdown'], true) ?? null;

            if ($costs) {
                foreach ($costs as &$cost) {
                    foreach ($cost as &$value) {
                        $value['total'] = FormQuantity::formatCurrency((float)$value['amount'] + (float)$value['vat']);
                        $value['amount'] = FormQuantity::formatCurrency((float)$value['amount']);
                        $value['vat'] = FormQuantity::formatCurrency((float)$value['vat']);
                    }
                }
                unset($cost, $value);
            }

            $payment = $payment->toArray(request());
            $payment['created_at_text'] = FormatDate::fullDateTime($payment['created_at']);
            $payment['total'] = FormQuantity::formatCurrency((float)$payment['total']);

            $pdf = Pdf::loadView('pdf.remaining-customer-payment-receipt', [
                'payment' => $payment,
                'costs' => $costs,
            ])->setPaper('A4', 'portrait');

            return $pdf->stream('remaining-payment-receipt-' . $payment['payment_folio'] . '.pdf');
        } catch (\Throwable $th) {
            return response(['error' => $th->getMessage()], 400);
        }
    }

    public function waterReceipt(int|string $id)
    {
        if (!is_numeric($id)) return response(['error' => 'Folio must be a number'], 400);

        $msc = MonthlyServiceCharge::findOrFail($id);

        if ((float)$msc->new_reading === 0) return response(['error' => 'Monthly does not have a reading yet'], 400);

        $customer = Customer::findOrFail($msc->customer_id);
        if ((string)$customer->service_type_id !== '2') return response(['error' => 'Invalid service type'], 400);

        $current_year = $msc->year ?? date('Y');
        $current_month = $msc->month ?? date('n');

        $months = [];
        $year = $current_year;
        for ($i = 0; $i < 6; $i++) {
            $months[] = ['year' => $year, 'month' => $current_month];
            $current_month--;
            if ($current_month === 0) {
                $current_month = 12;
                $year--;
            }
        }

        $reads_for_chart = MonthlyServiceCharge::select('month', 'year', 'new_reading', 'total_amount', 'excessive')
            ->where('customer_id', $customer->id)
            ->where(function ($query) use ($months) {
                foreach ($months as $m) {
                    $query->orWhere(function ($q) use ($m) {
                        $q->where('year', $m['year'])->where('month', $m['month']);
                    });
                }
            })
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $chart_data = $reads_for_chart->toArray(request());
        foreach ($chart_data as &$item) {
            $item['month_name'] = FormatDate::month((int)$item['month']);
            $item['new_reading'] = (int)$item['new_reading'] ?? 0;
            $item['excessive'] = (float)$item['excessive'] ?? 0;
            $item['total_amount'] = FormQuantity::formatCurrency((float)$item['total_amount'] ?? 0);
        }
        unset($item);

        $msc = $msc->toArray(request());
        $customer = $customer->toArray(request());
        $status = !empty($msc['is_paid']) ? 'PAGADO' : '';
        $subtotal_surcharge = $msc['water_surcharge'] + $msc['drainage_surcharge'] + $msc['excess_water_surcharge'] + $msc['excess_drainage_surcharge'];
        $subtotal_surcharge_discount = $msc['water_surcharge_discount'] + $msc['drainage_surcharge_discount'] + $msc['excess_water_surcharge_discount'] + $msc['excess_drainage_surcharge_discount'];

        $service_rate = MeasuredServiceRates::where('use_of_type_id', $customer['use_of_type_id'])
            ->where('classification_type_id', $customer['classification_type_id'])
            ->where('year', $year)
            ->first();

        if (!$service_rate)
            throw new \Exception("No se encontró el costo del servicio para el año $year.");

        $last_date = Carbon::create($msc['year'], $msc['month'], 1)->endOfMonth()->toDateString();

        $next_month = $msc['month'] + 1 > 12 ? 1 : $msc['month'] + 1;
        $next_year = $next_month === 1 ? $year + 1 : $year;

        $suspension_date = Carbon::create($next_year, $next_month, 1)->startOfMonth()->toDateString();

        $meter = WaterMeterFolios::where('customer_id', $customer['id'])
            ->where('folio_number', $customer['meter'])
            ->first();

        $pdf = Pdf::loadView('pdf.water-receipt', [
            'msc' => $msc,
            'status' => $status,
            'customer' => $customer,
            'month' => FormatDate::month((int)$msc['month']),
            'chart_data' => $chart_data,
            'water' => FormQuantity::formatCurrency($msc['water_amount']),
            'water_discount' => FormQuantity::formatCurrency($msc['water_discount']),
            'excess_water' => FormQuantity::formatCurrency($msc['excess_water_amount']),
            'subtotal_water' => FormQuantity::formatCurrency($msc['water_amount'] + $msc['excess_water_amount'] - $msc['water_discount'] - $msc['excess_water_discount']),
            'drainage' => FormQuantity::formatCurrency($msc['drainage_amount']),
            'drainage_discount' => FormQuantity::formatCurrency($msc['drainage_discount']),
            'drainage_subtotal' => FormQuantity::formatCurrency($msc['drainage_amount'] + $msc['excess_drainage_amount'] - $msc['drainage_discount'] - $msc['excess_drainage_discount']),
            'excess_drainage' => FormQuantity::formatCurrency($msc['excess_drainage_amount']),
            'surcharge' => FormQuantity::formatCurrency($subtotal_surcharge),
            'surcharge_discount' => FormQuantity::formatCurrency($subtotal_surcharge_discount),
            'subtotal_surcharge' => FormQuantity::formatCurrency($subtotal_surcharge - $subtotal_surcharge_discount),
            'vat' => FormQuantity::formatCurrency($msc['vat']),
            'total' => FormQuantity::formatCurrency($msc['total_amount']),
            'total_text' => FormQuantity::convertFloatToText($msc['total_amount']),
            'service_rate_excess' => FormQuantity::formatCurrency($service_rate->cost_to_exceed),
            'last_date' => FormatDate::fullDateTime($last_date),
            'suspension_date' => FormatDate::fullDateTime($suspension_date),
            'installation_date' => !empty($meter->installation_date) ? FormatDate::fullDateTime($meter->installation_date) : null,
        ])->setPaper('letter');

        return $pdf->stream('water-receipt.pdf');
    }

    public function userAgreement(int|string $id)
    {
        try {
            if (!is_numeric($id)) throw new \InvalidArgumentException('ID must be a number');
            $customer = Customer::findOrFail($id);
            if (!$customer->folio) throw new \Exception('No se puede generar el contrato, el cliente no tiene folio asignado.');

            $customer = new CustomerResource($customer);

            $customer_type = $customer->customerType->getAttributes()['name'];
            $use_of_type = $customer->useOfType->getAttributes()['name'];
            $classification_type = $customer->classificationType->getAttributes()['name'];

            $customer = $customer->toArray(request());
            $customer['customer_type'] = $customer_type;
            $customer['use_classification'] = $use_of_type . " - " . $classification_type;

            $customer['long_date'] = FormatDate::longDate(Carbon::now()->toDateString());
            $customer['agreement_date'] = FormatDate::fullDateTime($customer['created_at'] ?? Carbon::now()->toDateString());
            $pdf = Pdf::loadView('pdf.user-agreement', [
                'customer' => $customer,
            ])->setPaper('A4');
            return $pdf->stream('user-agreement.pdf');
        } catch (\Throwable $th) {
            return response(['error' => $th->getMessage()], 400);
        }
    }

    public function getNotice(int|string $id)
    {
        try {
            if (!is_numeric($id)) throw new \InvalidArgumentException('ID must be a number');
            $notice = Notice::findOrFail($id);

            $process_status = ProcessStatus::find($notice->process_status_id)->name;

            if ($process_status === 'Cancelado' || $process_status === 'Terminado')
                throw new \Exception('No se puede generar la notificación, la notificación está cancelada o concluida.');

            $customer = new CustomerResource($notice->customer);
            $notice_res = new NoticeResource($notice);
            $notice_arr = $notice_res->toArray(request());
            $notification_date = (string) FormatDate::fullDateTime($notice->created_at);
            $notice_arr['notification_date'] = $notification_date;
            $notice_arr['amount'] = FormQuantity::formatCurrency((float)$notice_arr['amount']);

            // dd($notice_arr);

            $pdf = Pdf::loadView('pdf.notice', [
                'notice' => $notice_arr,
                'customer' => $customer,
            ])->setPaper('A4');
            return $pdf->stream('notice.pdf');
        } catch (\Throwable $th) {
            return response(['error' => $th->getMessage()], 400);
        }
    }

    private function getPaymentData(Payment $payment): array
    {
        $data = $this->payment_service->getPayment($payment);
        $cleaned_data = new PaymentResource($data);
        $payment = $cleaned_data->toArray(request());

        $start_month = $payment['period']['start']['month'];
        $start_year = $payment['period']['start']['year'];
        $payment['initial_date'] = FormatDate::month($start_month) . ' ' . $start_year;
        $end_month = $payment['period']['end']['month'];
        $end_year = $payment['period']['end']['year'];
        $payment['final_date'] = FormatDate::month($end_month) . ' ' . $end_year;
        $payment['created_at_text'] = FormatDate::fullDateTime($payment['created_at']). ' ' . date('H:i:s', strtotime($payment['created_at']));
        $payment['amounts'] = $this->getAmounts($payment);

        return $payment;
    }

    private function getAmounts(array $data): array
    {
        $surcharge = (float) $data['water_surcharge'] + (float) $data['drainage_surcharge'];
        $surcharge_discount = (float) $data['water_surcharge_discount'] + (float) $data['drainage_surcharge_discount'];
        $subtotal_water = (float) $data['water'] - (float) $data['water_discount'];
        $subtotal_drainage = (float) $data['drainage'] - (float) $data['drainage_discount'];
        $subtotal_surcharge = (float) $surcharge - (float) $surcharge_discount;
        $total = (float) $data['total'];

        $amounts = [
            'water' => $data['water'],
            'water_surcharge' => $data['water_surcharge'],
            'water_discount' => $data['water_discount'],
            'drainage' => $data['drainage'],
            'drainage_surcharge' => $data['drainage_surcharge'],
            'drainage_discount' => $data['drainage_discount'],
            'vat' => $data['vat'],
            'total' => $data['total'],
            'surcharge' => $surcharge,
            'surcharge_discount' => $surcharge_discount,
            'subtotal_water' => $subtotal_water,
            'subtotal_drainage' => $subtotal_drainage,
            'subtotal_surcharge' => $subtotal_surcharge,
            'total' => $total,
        ];

        return $this->formatAmounts($amounts);
    }

    private function formatAmounts(array $amounts): array
    {
        return array_map(fn($amount) => FormQuantity::formatCurrency($amount), $amounts);
    }

    private function calculateCost(string $breakdown): array|null
    {
        $costs = json_decode($breakdown, true) ?? null;

        if ($costs) {
            foreach ($costs as &$cost) {
                foreach ($cost as &$value) {
                    $value['total'] = FormQuantity::formatCurrency((float)$value['amount'] + (float)$value['vat']);
                    $value['amount'] = FormQuantity::formatCurrency((float)$value['amount']);
                    $value['vat'] = FormQuantity::formatCurrency((float)$value['vat']);
                }
            }
            unset($cost, $value);
        }

        return $costs ?? null;
    }

    private function existsPayment($payment): void
    {
        if (!$payment) throw new \Exception('Payment not found');
        if ($payment->canceled) throw new \Exception('Payment has been canceled');
    }
}
