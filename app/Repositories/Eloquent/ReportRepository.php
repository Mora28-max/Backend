<?php

namespace App\Repositories\Eloquent;

use App\Models\Reports\Report;
use App\Traits\HasReportRelations;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\ReportRepositoryInterface;

class ReportRepository implements ReportRepositoryInterface
{
    use HasReportRelations;

    public function __construct(protected Report $report) {}

    public function getAllReports(int $pagination = 25): LengthAwarePaginator
    {
        return $this->report->with($this->reportRelations())
            ->orderBy('created_at', 'desc')
            ->paginate($pagination)
            ->withQueryString();
    }

    public function getReportsByCustomerId(int $customer_id, int $pagination = 25): LengthAwarePaginator
    {
        return $this->report->where('customer_id', $customer_id)->with($this->reportRelations())
            ->orderBy('created_at', 'desc')
            ->paginate($pagination)
            ->withQueryString();
    }

    public function findByTrackingFolio(string $tracking_folio): Report | null
    {
        return $this->report->where('tracking_folio', $tracking_folio)->first() ?? null;
    }
}
