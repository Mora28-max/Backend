<?php

namespace App\Repositories\Contracts;


use App\Models\Reports\Report;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReportRepositoryInterface
{
    public function getAllReports(int $pagination = 25): LengthAwarePaginator;
    public function getReportsByCustomerId(int $customerId, int $pagination = 25): LengthAwarePaginator;
    public function findByTrackingFolio(string $tracking_folio): ?Report;
}
