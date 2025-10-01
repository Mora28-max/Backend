<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\ReportChildSubcategory;
use App\Http\Resources\Shared\GenericCollection;

class ReportChildSubcategoryController extends Controller
{
    public function index()
    {

        $data = ReportChildSubcategory::select('id', 'name', 'report_subcategory_id')->get();
        return new GenericCollection($data);
    }
}
