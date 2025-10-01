<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\ServiceStatus;
use App\Http\Resources\Shared\GenericCollection;

class ServiceStatusController extends Controller
{
    public function index()
    {
        $data = ServiceStatus::select('id', 'name', 'color')->get();
        return new GenericCollection($data);
    }
}
