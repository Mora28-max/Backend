<?php

namespace App\Http\Controllers\Catalogs;

use App\Models\Reports\Unities;
use App\Http\Controllers\Controller;
use App\Http\Resources\Shared\GenericCollection;
use Illuminate\Http\Request;

class UnitiesController extends Controller
{
    public function index()
    {
          $data = Unities::select('id', 'name', 'abbreviation')->get();
        return new GenericCollection($data);

        }
}
   