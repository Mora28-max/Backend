<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDF\ReportController;
use App\Http\Controllers\PDF\ReceiptController;

Route::get('/customer-view/pdf/receipt/{id}', [ReceiptController::class, 'getCustomerPaymentReceipt']);
Route::get('/customer-view/pdf/remaining-receipt/{id}', [ReceiptController::class, 'getCustomerRemainingPaymentReceipt']);
Route::get('/customer-view/pdf/water-receipt/{id}', [ReceiptController::class, 'waterReceipt']);
