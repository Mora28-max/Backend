<?php

namespace App\Http\Resources\Maintenance;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MaintenanceHistoryCollection extends ResourceCollection
{
    public $collects = MaintenanceHistoryResource::class;   

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
