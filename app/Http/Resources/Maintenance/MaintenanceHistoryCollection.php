<?php

namespace App\Http\Resources\Maintenance;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MaintenanceHistoryCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'total' => $this->total(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
            ],
        ];
    }
}
