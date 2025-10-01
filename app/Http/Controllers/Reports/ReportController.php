<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Models\Reports\Report;
use App\DTOs\Report\ReportData;
use App\Traits\HasReportRelations;
use App\Http\Controllers\Controller;
use App\Services\Report\ReportService;
use App\Http\Resources\Report\ReportResource;
use App\Http\Resources\Report\ReportCollection;
use App\Http\Requests\Report\StoreReportRequest;
use App\Http\Requests\Report\UpdateReportRequest;
use App\Http\Requests\Report\VerifyStatusRequest;
use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReportController extends Controller
{
    use AuthorizesRequests, HasReportRelations;

    public function __construct(
        protected ReportService $report_service,
        protected ReportRepositoryInterface $report_repository
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Report::class);

        $reports = !empty($request->customerId) ?
            $this->report_repository->getReportsByCustomerId($request->customerId) :
            $this->report_repository->getAllReports();

        return new ReportCollection($reports);
    }

    public function store(StoreReportRequest $request)
    {
        try {
            $this->authorize('create', Report::class);
            $report = $this->report_service->createReport(new ReportData($request->validated()));
            $report = $report->load($this->reportRelations());
            return response([
                'message' => 'El reporte ha sido creado correctamente.',
                'data' => new ReportResource($report)
            ], 201);
        } catch (\Throwable $th) {
            return response([
                'message' => 'Error al crear el reporte: ' . $th->getMessage()
            ], 500);
        }
    }

    public function show(Report $report)
    {
        $this->authorize('view', $report);
        return new ReportResource($report->load($this->reportRelations()));
    }

    public function update(UpdateReportRequest $request, Report $report)
    {
        try {
            $this->authorize('update', $report);
            $report = $this->report_service->updateReport($report, $request->validated());
            return response([
                'message' => 'Reporte actualizado correctamente.',
                'data' => $report->load($this->reportRelations())
            ], 200);
        } catch (\Throwable $th) {
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Report $report)
    {
        try {
            $this->authorize('delete', $report);
            $this->report_service->cancelReport($report);
            return response(['message' => 'Reporte cancelado correctamente.']);
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()], 500);
        }
    }

    public function shouldBePaid(Report $report)
    {
        try {
            $this->report_service->shouldBePaid($report);
            return response(['message' => 'El reporte ha sido registrado a obligatorio ser pagado.']);
        } catch (\Throwable $th) {
            return response([
                'message' => $th->getMessage()
            ], 409);
        }
    }

    public function filterReports(Request $request)
    {
        $this->authorize('viewAny', Report::class);
        $reports = $this->report_service->getAllReports($request->all());
        return new ReportCollection($reports);
    }

    public function getGeoData(Request $request)
    {
        $this->authorize('viewAny', Report::class);
        $reports = $this->report_service->getReportsGeoData($request->all());
        return response([
            "data" => $reports
        ]);
    }

    public function verifyReportStatus(VerifyStatusRequest $request)
    {
        try {
            $this->report_service->verifyReportStatus($request->validated());
            return response(['message' => 'Proceso verificado.']);
        } catch (\Throwable $th) {
            return response([
                'message' => 'No modifiqué el usuario porque el proceso esta incompleto.',
                'error' => $th->getMessage()
            ]);
        }
    }
}
