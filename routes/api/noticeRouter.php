<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Notices\NoticeController;


Route::post('/noncompliance-notice/{notice}', [NoticeController::class, 'nonCompliance']);
Route::apiResource('/notices', NoticeController::class);
