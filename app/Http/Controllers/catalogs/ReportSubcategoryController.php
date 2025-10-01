<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\ReportSubcategory;
use App\Http\Resources\Shared\GenericCollection;

class ReportSubcategoryController extends Controller
{

    public function index()
    {

        $data = ReportSubcategory::select('id', 'name', 'report_category_id')->get();
        return new GenericCollection($data);
    }
}
