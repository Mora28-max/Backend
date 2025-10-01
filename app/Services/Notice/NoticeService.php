<?php

namespace App\Services\Notice;

use Carbon\Carbon;
use App\Helpers\FormatDate;
use App\Helpers\FormQuantity;
use App\Models\Notice\Notice;
use App\Models\Reports\Report;
use App\Models\Customers\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Pivots\NoticeReport;
use Illuminate\Support\Facades\Auth;
use App\Helpers\GenerateTrackingFolio;
use App\Models\Catalogs\ProcessStatus;
use App\Helpers\UploadDataToCloudinary;
use App\Models\Payments\RemainingPayment;
use App\Validators\Notice\NoticeValidator;
use App\Models\Catalogs\NoticeAmountPerYear;
use App\Models\Payments\MonthlyServiceCharge;
use FontLib\TrueType\Collection;

class NoticeService
{
    private $vat_value = 0.16;

    public function createNotice(array $data): Notice
    {
        NoticeValidator::ensureNoActiveNotice($data['customer_id']);
        return DB::transaction(function () use ($data) {
            $data['user_id'] = Auth::user()->id;
            $data['process_status_id'] = 1;
            $data['notice_type_id'] = 3;
            $data['period'] = $this->getPeriod($data['customer_id']);
            $data['tracking_folio'] = GenerateTrackingFolio::generateTrackingFolioNotice();
            $notice = Notice::create($data);
            $customer = Customer::findOrFail($data['customer_id']);
            $this->markCustomerAsNotified($customer);
            return $notice;
        });
    }

    public function updateNotice(Notice $notice, array $data): Notice
    {
        $process_status = ProcessStatus::findOrFail($notice->process_status_id)->name;
        NoticeValidator::ensureNotClosed($process_status);
        NoticeValidator::ensureNotCanceled($process_status);

        return DB::transaction(function () use ($notice, $data) {
            $process_status_data = ProcessStatus::findOrFail($data['process_status_id'])->name;
            $is_closed = $process_status_data === "Cerrado";
            $is_canceled = $process_status_data === "Cancelado";

            if (!empty($data['evidence'])) {
                $data['evidence'] = $this->uploadEvidence($notice->tracking_folio, $data['evidence']);
            }
            if ($is_closed) {
                $this->handleFinishedNotice($notice, $data);
            } elseif ($is_canceled) {
                $this->handleCanceledNotice($notice, $data);
            } else {
                $notice->update($data);
            }
            return $notice;
        });
    }

    public function deleteNotice(Notice $notice): void
    {
        NoticeValidator::ensureNotFinished($notice->process_status_id);
        $notice->delete();
    }

    public function generateReportForNoncompliance(Notice $notice, array $data): array
    {
        return DB::transaction(function () use ($notice, $data) {
            $report_data = $this->createReport($notice, $data);
            $notice->process_status_id = 5;
            $notice->is_reported = 1;
            $notice->save();
            $this->markCustomerAsNotNotified($notice->customer);
            $this->createNoticeReport($notice, $report_data);
            return [$report_data['description'], $report_data['report_id']];
        });
    }

    private function getPeriod(int $customer_id): string
    {
        $before_month = Carbon::now()->month - 1;
        $months_behind = MonthlyServiceCharge::select('month', 'year')
            ->where('customer_id', $customer_id)
            ->where('monthly_payment_statement_id', 2)
            ->where(function ($query) use ($before_month) {
                $query->where('year', '<', date('Y'))
                    ->orWhere(function ($query) use ($before_month) {
                        $query->where('year', date('Y'))
                            ->where('month', '<=', $before_month);
                    });
            })
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        if ($months_behind->isEmpty())
            throw new \Exception('No hay meses rezagados adeudados para este usuario.');

        $first = $months_behind->first();
        $last = $months_behind->last();
        $first_month_name = FormatDate::month($first->month) . ' ' . $first->year;
        $last_month_name = FormatDate::month($last->month) . ' ' . $last->year;

        return "{$last_month_name} - {$first_month_name}";
    }

    private function markCustomerAsNotified(Customer $customer): void
    {
        $customer->is_notified = true;
        $customer->save();
    }

    private function markCustomerAsNotNotified(Customer $customer): void
    {
        $customer->is_notified = false;
        $customer->save();
    }

    private function uploadEvidence(string $folio, $evidence): string
    {
        $explode = explode('/', $folio);
        $image_name = 'notice-' . $explode[1];
        return UploadDataToCloudinary::uploadImage($image_name, $evidence, 'notices');
    }

    private function handleFinishedNotice(Notice $notice, array $data): void
    {
        $noticeAmountPerYear = NoticeAmountPerYear::where('year', date('Y'));
        if (!$noticeAmountPerYear->exists())
            throw new \Exception('No se ha definido el monto de la notificación para el año actual.');

        $amountYear = $noticeAmountPerYear->first()->amount;

        $this->createRemainingPayment($notice, $amountYear);
        $data['cost'] = $amountYear + $this->vat_value * $amountYear;
        $data['notification_date'] = Carbon::now();
        $notice->update($data);
    }

    private function handleCanceledNotice(Notice $notice, array $data): void
    {
        $notice->process_status_id = 4;
        $notice->save();
        $customer = Customer::findOrFail($notice->customer_id);
        $customer->is_notified = false;
        $customer->save();
    }

    private function createRemainingPayment(Notice $notice, float $amountYear): void
    {
        RemainingPayment::create([
            'customer_id' => $notice->customer_id,
            'tracking_folio' => $notice->tracking_folio,
            'subtotal' => $amountYear,
            'vat' => $this->vat_value * $amountYear,
            'total' => $amountYear + $this->vat_value * $amountYear,
            'note' => $notice->comment ?? '',
            'monthly_payment_statement_id' => 2,
        ]);
    }

    private function createReport(Notice $notice, array $data): array
    {
        $num_float = (float) $notice->amount;
        $currency = FormQuantity::formatCurrency($num_float);
        $description = "Se realiza reporte de suspensión de agua y/o drenaje debido a que el usuario tiene una notificación con folio {$notice->tracking_folio} vencida. Monto: {$currency}. Periodo: {$notice->period}";
        $report = Report::create([
            'customer_id' => $notice->customer_id,
            'user_id' => Auth::user()->id,
            'tracking_folio' => GenerateTrackingFolio::generateTrackingFolioReport($data['notice_classif']),
            'report_category_id' => $data['notice_classif'],
            'report_subcategory_id' => (string) $data['notice_classif'] === "1" ? 6 : 15,
            'report_child_subcategory_id' => (string) $data['notice_classif'] === "1" ? 19 : 54,
            "report_priority_id" => 2,
            'process_status_id' => 1,
            "description" => $description,
        ]);
        return [
            'description' => $description,
            'report_id' => $report->id,
        ];
    }

    private function createNoticeReport(Notice $notice, array $report_data): void
    {
        NoticeReport::create([
            'notice_id' => $notice->id,
            'report_id' => $report_data['report_id'],
            'customer_id' => $notice->customer_id,
        ]);
    }
}
