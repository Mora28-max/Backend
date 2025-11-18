<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\ResourceCollection;

class GoodsCollection extends ResourceCollection
{
    /**
     * Personalizar la estructura de la colección.
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
