<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthenticateCustomer;
use App\Http\Controllers\CustomerView\AuthCustomerController;
use App\Http\Controllers\CustomerView\CustomerProfileController;

Route::middleware([AuthenticateCustomer::class])->group(function () {
    Route::get('/customer-view/{customer}', [CustomerProfileController::class, 'getCustomerData']);
    Route::get('/customer-view/statistics/{id}', [CustomerProfileController::class, 'getStatistics']);
    Route::get('/customer-view/reports/{id}', [CustomerProfileController::class, 'getReports']);
    Route::get('/customer-view/payments/{customer_id}', [CustomerProfileController::class, 'getPayments']);
    Route::get('/customer-view/payment/{id}', [CustomerProfileController::class, 'getPayment']);
    Route::get('/customer-view/remaining-payments/{id}', [CustomerProfileController::class, 'getRemainingPayments']);
    Route::post('/customer-view/verify-token', [AuthCustomerController::class, 'verifyToken']);
    Route::post('/customer-view/logout', [AuthCustomerController::class, 'customerLogout']);
    Route::get('/customer-view/water-receipts/{id}', [CustomerProfileController::class, 'getWaterReceipts']);
    require_once __DIR__ . '/pdfCustomerRouter.php';
});

Route::post('/customer-view/login', [AuthCustomerController::class, 'customerLogin']);
Route::post('/customer-view/register', [AuthCustomerController::class, 'customerRegister']);
