<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class InventoryMaterialCollection extends ResourceCollection
{
    public $collects = InventoryMaterialResource::class;

    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
