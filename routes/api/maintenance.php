<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Maintenance\TypeMaintenanceController;
use App\Http\Controllers\Maintenance\MaintenanceHistoryController;

/*
|--------------------------------------------------------------------------
| Maintenance API routes
|--------------------------------------------------------------------------
|
| Rutas agrupadas bajo el prefijo /api/maintenance
| Estilo similar al módulo Inventory que ya conoces
|
*/

Route::prefix('maintenance')->name('maintenance.')->group(function () {

    // Type Maintenances
    Route::apiResource('type_maintenances', TypeMaintenanceController::class);

    // Maintenance Histories (similar a tu versión anterior)
    Route::get('maintenance_histories', [MaintenanceHistoryController::class, 'index']);
    Route::post('maintenance_histories', [MaintenanceHistoryController::class, 'store']);
    Route::get('maintenance_histories/{id}', [MaintenanceHistoryController::class, 'show']);
    Route::post('maintenance_histories/{id}', [MaintenanceHistoryController::class, 'update']); // antes era POST, ahora PUT
    Route::delete('maintenance_histories/{id}', [MaintenanceHistoryController::class, 'destroy']);
});
