<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Auth\InvitationController;
use App\Http\Controllers\WaterTank\TankLogController;
use App\Http\Controllers\ActivityLog\ActivityLogController;
use App\Http\Controllers\Audit\CashRegisterAuditController;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/register/invitation', [InvitationController::class, 'sendInvitation']);
    Route::apiResource('/users', UserController::class);
    Route::post('/assign-role', [UserController::class, 'assignRole']);
    Route::get('/logs', [ActivityLogController::class, 'index']);
    Route::get('/cash-register-audits/amounts', [CashRegisterAuditController::class, 'getAmountByDateRange']);
    Route::apiResource('/cash-register-audits', CashRegisterAuditController::class)->except(['update']);
    Route::apiResource('/water-tank-logs', TankLogController::class)->except(['show', 'update']);

    include __DIR__ . '/api/customerRouter.php';
    include __DIR__ . '/api/reportRouter.php';
    include __DIR__ . '/api/paymentRouter.php';
    include __DIR__ . '/api/catalogRouter.php';
    include __DIR__ . '/api/locationRouter.php';
    include __DIR__ . '/api/meterReadingRouter.php';
    include __DIR__ . '/api/noticeRouter.php';
    include __DIR__ . '/api/agreementRouter.php';
    include __DIR__ . '/api/pdfRouter.php';
});

//Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Authentication Customer Routes
include __DIR__ . '/api/customerViewRouter.php';
