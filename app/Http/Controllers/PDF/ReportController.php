<?php

namespace App\Http\Controllers\PDF;

use App\Helpers\FormatDate;
use App\Models\Reports\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Http\Resources\Report\MaterialUsedCollection;
use App\Http\Resources\Report\ReportResource;
use App\Models\Reports\MaterialUsed;

class ReportController extends Controller
{
    public function getReport(int|string $report_id)
    {
        if (!is_numeric($report_id)) {
            return response(['error' => 'Report ID must be a number'], 400);
        }

        $report = Report::findOrFail($report_id);
        $report = new ReportResource($report);
        $materials = MaterialUsed::where('report_id', $report_id)->get();
        $materials = new MaterialUsedCollection($materials);
        $reportArray = $report->toArray(request());
        $reportArray['created_at_text'] = FormatDate::fullDateTime($reportArray['created_at']);

        $pdf = Pdf::setOptions(['isRemoteEnabled' => true])
            ->loadView('pdf.report', [
                'report' => $reportArray,
                'materials' => $materials->toArray(request()),
            ])->setPaper('letter');

        return $pdf->stream('report.pdf');
    }
}
