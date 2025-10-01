<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Locations\ZoneController;
use App\Http\Controllers\Locations\ColonyController;

Route::apiResource('/zones', ZoneController::class)->except(['show']);
Route::apiResource('/colonies', ColonyController::class)->except(['show']);
