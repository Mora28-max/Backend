<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Agreements\AgreementController;


Route::get('/payment-agreements/filter-date', [AgreementController::class, 'filterByDate']);
Route::apiResource('/payment-agreements', AgreementController::class)->except(['update']);
Route::get('/payment-agreements/customer/{id}', [AgreementController::class, 'listPaymentDates']);
Route::post('/payment-agreements/breakdown_agreement/{id}', [AgreementController::class, 'payAgreementPartial']);
Route::post('/payment-agreements/report/{id}', [AgreementController::class, 'createReportForNonCompliantAgreements']);
