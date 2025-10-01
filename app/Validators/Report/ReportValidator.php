<?php

namespace App\Validators\Report;

use App\DTOs\Report\ReportData;
use App\Models\Reports\Report;
use App\Models\Catalogs\ProcessStatus;
use App\Models\Catalogs\ReportSubcategory;
use App\Models\Catalogs\ReportChildSubcategory;

class ReportValidator
{
    public static function validateCategoryRelations(ReportData $dto)
    {
        $subcategory = ReportSubcategory::where('id', $dto->report_subcategory_id)
            ->where('report_category_id', $dto->report_category_id)
            ->exists();

        $child_subcategory = ReportChildSubcategory::where('id', $dto->report_child_subcategory_id)
            ->where('report_subcategory_id', $dto->report_subcategory_id)
            ->exists();

        if (!$subcategory || !$child_subcategory) {
            throw new \Exception('La categoría, subcategoría ó la subcategoría secundaria no cuentan con una relación de pertenencia.');
        }
    }

    public static function validateUpdateRelations(array $data, Report $report): void
    {
        $category = $data['report_category_id'] ?? $report->report_category_id;
        $subcategory = $data['report_subcategory_id'] ?? $report->report_subcategory_id;
        $child_subcategory = $data['report_child_subcategory_id'] ?? $report->report_child_subcategory_id;
        $array = [$category, $subcategory, $child_subcategory];

        if (collect($array)->contains(fn($item) => empty($item))) {
            throw new \Exception('Para actualizar el reporte es necesario proporcionar la categoría, la subcategoría y la subcategoría secundaria.');
        }

        $belongsToCategory = ReportSubcategory::where('id', $subcategory)
            ->where('report_category_id', $category)
            ->exists();

        $belongsToSubcategory = ReportChildSubcategory::where('id', $child_subcategory)
            ->where('report_subcategory_id', $subcategory)
            ->exists();

        if (!$belongsToCategory || !$belongsToSubcategory) {
            throw new \Exception('La categoría, subcategoría ó la subcategoría secundaria no cuentan con una relación de pertenencia.');
        }
    }

    public static function validateisFinishedOrCanceled(array $data, Report $report): void
    {
        $process_status = ProcessStatus::findOrFail($report->process_status_id);
        $ended = $process_status->name === "Cancelado" || $process_status->name === "Terminado";

        if ($ended)
            throw new \Exception('El reporte no puede ser modificado ya que se encuentra terminado o cancelado.');
    }

    public static function validateCustomerIdExists(Report $report): void
    {
        if (!$report->customer_id)
            throw new \Exception("El reporte debe tener un usuario vinculado para colocar como pagado");
    }

    public static function validateFinishedAndShouldBePaid(array $data, Report $report): void
    {
        if ($report->should_be_paid) {
            $data_process_status_id = $data['process_status_id'] ?? $report->process_status_id;
            $data_process_status = ProcessStatus::findOrFail($data_process_status_id);
            $is_breakdown_completed = !empty($data['breakdown']) || !empty($data['subtotal']) || !empty($data['vat']);

            if ($data_process_status === 'Terminado' && !$is_breakdown_completed)
                throw new \Exception("No se puede terminar el reporte ya que se requiere el desglose de pagos.");
        }
    }

    public static function validateCustomerAssociation(array $data, Report $report): void
    {
        $process_status_id = $data['process_status_id'] ?? $report->process_status_id;
        $report_subcategory_id = $data['report_subcategory_id'] ?? $report->report_subcategory_id;
        $report_child_subcategory_id = $data['report_child_subcategory_id'] ?? $report->report_child_subcategory_id;

        $process_status = ProcessStatus::findOrFail($process_status_id);
        $is_closed = $process_status->name === "Cerrado";

        $report_subcategory = ReportSubcategory::findOrFail($report_subcategory_id);
        $is_suspended = $report_subcategory->name === "Suspensión";

        $report_child_subcategory = ReportChildSubcategory::findOrFail($report_child_subcategory_id);
        $is_voluntary_suspension = $report_child_subcategory->name === "Suspensión Voluntaria";

        $is_finalized = $process_status->name === "Cerrado" || $process_status->name === "Terminado";

        if ($is_suspended && $is_closed) {
            if (!$report->customer_id)  throw new \Exception('El reporte debe tener un usuario asociado.');
        }

        if ($is_voluntary_suspension && $is_finalized) {
            if (!$report->customer_id)
                throw new \Exception('El reporte debe tener un usuario asociado.');
        }
    }
}
