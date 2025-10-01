<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Reports\NoteController;
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Reports\MaterialController;
use App\Http\Controllers\Reports\MaterialUsedController;

//Report Routes
Route::post('/report-be-paid/{report}', [ReportController::class, 'shouldBePaid']);
Route::post('/filter-reports', [ReportController::class, 'filterReports']);
Route::post('/verify-report-status', [ReportController::class, 'verifyReportStatus']);
Route::apiResource('/reports', ReportController::class);
Route::get('/reports-geo-data', [ReportController::class, 'getGeoData']);
Route::post('/report-notes/{id}/images', [NoteController::class, 'addImagesForPdf']);
Route::apiResource('/report-notes', NoteController::class);
Route::apiResource('/report-materials', MaterialController::class)->except(['show', 'update']);
Route::apiResource('/material-used', MaterialUsedController::class)->except(['show', 'update']);
