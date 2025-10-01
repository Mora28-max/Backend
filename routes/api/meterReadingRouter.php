<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Readings\ReadingsController;
use App\Http\Controllers\Readings\MeterReadingScheduleController;



Route::get('/meter-reading-finished', [MeterReadingScheduleController::class, 'showFinishReading']);
Route::apiResource('/meter-reading-schedules', MeterReadingScheduleController::class);
Route::apiResource('/readings', ReadingsController::class)->except(['store', 'destroy']);
Route::post('/readings/filter', [ReadingsController::class, 'filterMonthlyServiceCharges']);
