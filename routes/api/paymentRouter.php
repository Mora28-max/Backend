<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Payments\RemainingPaymentsController;
use App\Http\Controllers\Payments\AdditionalPaymentsController;
use App\Http\Controllers\Payments\MonthlyServiceChargeController;

Route::get('/monthly-service-charges/forgiveness/{id}', [MonthlyServiceChargeController::class, 'showForgiven']);
Route::apiResource('/monthly-service-charges', MonthlyServiceChargeController::class)->except(['destroy']);
Route::post('/filter-msc', [MonthlyServiceChargeController::class, 'filterMonthlyServiceCharges']);
Route::apiResource('/payments', PaymentController::class);
Route::apiResource('/remaining-payments', RemainingPaymentsController::class);
Route::post('/pay-remaining-payment', [RemainingPaymentsController::class, 'payRemainingPayment']);
Route::apiResource('/additional-payments', AdditionalPaymentsController::class);
