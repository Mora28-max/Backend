<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customers\CommitteeController;
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Customers\ClientBackupContactController;

Route::apiResource('/customers', CustomerController::class);
Route::apiResource('/committees', CommitteeController::class);
Route::apiResource('/beneficiaries', ClientBackupContactController::class);
