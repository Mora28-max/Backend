<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDF\ReportController;
use App\Http\Controllers\PDF\ReceiptController;
use App\Http\Controllers\PDF\CashRegisterController;

Route::get('/pdf/user-agreement/{id}', [ReceiptController::class, 'userAgreement']);
Route::get('/pdf/receipt/{id}', [ReceiptController::class, 'getPaymentReceipt']);
Route::get('/pdf/remaining-receipt/{id}', [ReceiptController::class, 'getRemainingPaymentReceipt']);
Route::get('/pdf/additional-receipt/{id}', [ReceiptController::class, 'getAdditionalPayment']);
Route::get('/pdf/water-receipt/{id}', [ReceiptController::class, 'waterReceipt']);
Route::get('/pdf/report/{id}', [ReportController::class, 'getReport']);
Route::get('/pdf/cash-register-audit/{id}', [CashRegisterController::class, 'downloadAuditReport']);
Route::get('/pdf/notice/{id}', [ReceiptController::class, 'getNotice']);
