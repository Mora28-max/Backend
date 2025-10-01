<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shared\GenericCollection;

class GenericCatalogController extends Controller
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $data = $this->model::select('id', 'name')->get();
        return new GenericCollection($data);
    }
}
