<?php

namespace App\Services\Report;

use Carbon\Carbon;
use App\Helpers\GeoHelper;
use App\Models\Reports\Report;
use App\DTOs\Report\ReportData;
use App\Models\Customers\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Pivots\NoticeReport;
use App\Helpers\GenerateTrackingFolio;
use App\Models\Catalogs\ProcessStatus;
use App\Models\Payments\RemainingPayment;
use App\Models\Catalogs\ReportSubcategory;
use App\Validators\Report\ReportValidator;
use App\Models\Catalogs\ReportChildSubcategory;
use App\Models\Payments\MonthlyServiceCharge;
use App\Repositories\Contracts\ReportRepositoryInterface;

class ReportService
{

    public function __construct(protected ReportRepositoryInterface $report_repository) {}

    public function getAllReports(?array $data)
    {
        $search = $data['search'] ?? '';
        $categories = $data['categories'] ?? [];
        $subcategories = $data['subcategories'] ?? [];
        $childSubcategories = $data['child_subcategories'] ?? [];
        $priorities = $data['priorities'] ?? [];
        $processStatuses = $data['process_statuses'] ?? [];
        $date = $data['date'] ?? '';

        $query = Report::with(['customer', 'reportCategory', 'reportSubcategory', 'reportPriority', 'processStatus'])
            ->search($search)
            ->category($categories)
            ->subcategory($subcategories)
            ->childSubcategory($childSubcategories)
            ->priority($priorities)
            ->processStatus($processStatuses);

        if ($date) {
            $carbonDate = Carbon::parse($date);
            $query->whereYear('created_at', $carbonDate->year)
                ->whereMonth('created_at', $carbonDate->month);
        }

        return $query->orderBy('id', 'ASC')->paginate(25)->withQueryString();
    }

    public function createReport(ReportData $dto): Report
    {
        ReportValidator::validateCategoryRelations($dto);
        $data = $dto->toArray();
        $data['tracking_folio'] = GenerateTrackingFolio::generateTrackingFolioReport($data['report_category_id']);
        return DB::transaction(function () use ($dto, $data) {
            $this->updateCustomerStatusByReconnection($dto);

            $address = $this->getAddress($dto);
            $geo_data = GeoHelper::getLatLngFromAddress($address);

            if ($geo_data) {
                $data['lat'] = $geo_data['lat'];
                $data['lng'] = $geo_data['lng'];
            }

            $report = Report::create($data);
            $this->UpdateCustomerStatusForVoluntarySuspension($report);
            return $report;
        });
    }

    public function updateReport(Report $report, array $data): Report
    {
        ReportValidator::validateFinishedAndShouldBePaid($data, $report);
        ReportValidator::validateUpdateRelations($data, $report);
        ReportValidator::validateisFinishedOrCanceled($data, $report);
        ReportValidator::validateCustomerAssociation($data, $report);

        return DB::transaction(function () use ($report, $data) {
            $process_status_id = $data['process_status_id'] ?? $report->process_status_id;
            $process_status = ProcessStatus::findOrFail($process_status_id);
            $is_finished = $process_status->name === 'Cerrado' || $process_status->name === 'Terminado';
            if ($is_finished) $this->finalizeNoticeReport($report);

            if (!empty($data['customer_id'])) {
                $this->updateCustomerId($report, $data['customer_id']);
            }

            if (!empty($data['report_category_id'])) {
                if ((string)$report->report_category_id !== (string)$data['report_category_id']) {
                    $data['tracking_folio'] = GenerateTrackingFolio::generateTrackingFolioReport($data['report_category_id']);
                }
            }

            if (!empty($data['breakdown'])) {
                $tracking_folio = $data['tracking_folio'] ?? $report->tracking_folio;
                $this->updateOrCreateRemainingPayment($data, $tracking_folio, $report->customer_id);
            }

            $report->update($data);
            $report->refresh();
            $this->UpdateCustomerStatusForSuspension($report);

            return $report;
        });
    }

    public function shouldBePaid(Report $report): void
    {
        ReportValidator::validateCustomerIdExists($report);
        $report->should_be_paid = true;
        $report->save();
    }

    public function cancelReport(Report $report): void
    {
        $report->process_status_id = 4;
        $report->save();
    }

    public function getReportsGeoData(array $request): object
    {
        $category_id = $request['category_id'] ?? 1;
        $subcategory_id = $request['subcategory_id'] ?? 1;
        $child_subcategory_id = $request['child_subcategory_id'] ?? 1;
        $process_status_id = $request['process_status_id'] ?? 1;
        $priority_id = $request['priority_id'] ?? 2;

        $reports = Report::select('id', 'tracking_folio', 'lat', 'lng')
            ->whereNotNull('lat')
            ->where('report_category_id', $category_id)
            ->where('report_subcategory_id', $subcategory_id)
            ->where('report_child_subcategory_id', $child_subcategory_id)
            ->where('process_status_id', $process_status_id)
            ->where('report_priority_id', $priority_id)
            ->get();

        return $reports;
    }

    public function verifyReportStatus(array $data): void
    {
        $report = $this->report_repository->findByTrackingFolio($data['tracking_folio']);

        if (empty($report->customer_id)) throw new \Exception('El reporte debe tener un usuario asociado.');

        $process_status = ProcessStatus::findOrFail($report->process_status_id);
        if ($process_status->name !== 'Cerrado' && $process_status->name !== 'Terminado')
            throw new \Exception('El reporte no se encuentra cerrado o terminado.');

        $is_similar_report =
            (string) $report->report_category_id === (string) $data['report_category_id'] &&
            (string) $report->report_subcategory_id === (string) $data['report_subcategory_id'] &&
            (string) $report->report_child_subcategory_id === (string) $data['report_child_subcategory_id'];

        if (!$is_similar_report) throw new \Exception('El reporte no coincide con tipo, categoría o subcategoría.');
    }

    private function updateCustomerStatusByReconnection(ReportData $dto): void
    {
        $report_subcategory = ReportSubcategory::find($dto->report_subcategory_id);
        $report_child_subcategory = ReportChildSubcategory::find($dto->report_child_subcategory_id);

        $isReconnection = $report_subcategory->name === 'Reconexión';
        $isVoluntaryReconnection = $report_child_subcategory->name === 'Reconexión Voluntaria';

        if ($isReconnection || $isVoluntaryReconnection) {
            if (!$dto->customer_id)  throw new \Exception('El reporte debe tener un usuario asociado.');
            $customer = Customer::findOrFail($dto->customer_id);
            $customer->service_status_id = 1;
            $customer->save();
        }
    }
    private function UpdateCustomerStatusForVoluntarySuspension(Report $report): void
    {
        $report_child_subcategory = ReportChildSubcategory::find($report->report_child_subcategory_id);
        $isVoluntarySuspension = $report_child_subcategory->name === 'Suspensión Voluntaria';

        if ($isVoluntarySuspension) {
            $customer = Customer::findOrFail($report->customer_id);
            $customer->service_status_id = 4;
            $customer->save();

            $msc = MonthlyServiceCharge::where('customer_id', $report->customer_id)
                ->where('canceled', false)
                ->where('payment_folio', null)
                ->where('monthly_payment_statement_id', 2)
                ->get();

            foreach ($msc as $charge) {
                $charge->monthly_payment_statement_id = 5;
                $charge->save();
            }
        }
    }

    private function UpdateCustomerStatusForSuspension(Report $report): void
    {
        $report_subcategory = ReportSubcategory::find($report->report_subcategory_id);
        $isSuspension = $report_subcategory->name === 'Suspensión';

        if ($isSuspension) {
            $customer = Customer::findOrFail($report->customer_id);
            $customer->service_status_id = 2;
            $customer->save();

            if (!$report->customer_id) throw new \Exception('El reporte debe tener un usuario asociado.');
            $customer = Customer::findOrFail($report->customer_id);
            $customer->service_status_id = 2;
            $customer->save();
        }
    }

    private function updateOrCreateRemainingPayment(array $data, string $tracking_folio, $customer_id): void
    {

        $existing = RemainingPayment::where('tracking_folio', $tracking_folio)
            ->whereNull('payment_folio')
            ->where('canceled', false)
            ->where('monthly_payment_statement_id', 2)
            ->first();

        if ($existing) {
            $existing->breakdown = $data['breakdown'];
            $existing->subtotal = $data['subtotal'];
            $existing->vat = $data['vat'];
            $existing->total = $data['subtotal'] + $data['vat'];
            $existing->save();
        } else {
            RemainingPayment::create([
                'customer_id' => $customer_id,
                'tracking_folio' => $tracking_folio,
                'breakdown' => $data['breakdown'],
                'subtotal' => $data['subtotal'],
                'vat' => $data['vat'],
                'total' => $data['subtotal'] + $data['vat'],
                'monthly_payment_statement_id' => 2,
            ]);
        }
    }

    private function finalizeNoticeReport(Report $report)
    {
        $notice_report = NoticeReport::where('report_id', $report->id)
            ->where('finished_process', false)
            ->first();

        if ($notice_report) {
            $notice_report->finished_process = true;
            $notice_report->save();

            $customer = Customer::find($notice_report->customer_id);
            if ($customer) {
                $customer->is_notified = false;
                $customer->service_status_id = 2;
                $customer->save();
            }
        }
    }

    private function updateCustomerId(Report $report, int $customer_id)
    {
        $report->customer_id = $customer_id;
        $report->name = "";
        $report->phone = "";
        $report->address = "";
        $report->save();
    }

    private function getAddress(ReportData $dto): string
    {
        if ($dto->customer_id) {
            $customer = Customer::findOrFail($dto->customer_id);
            return $customer->address;
        }
        return $dto->address;
    }
}
