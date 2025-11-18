<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryMaterialResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code_materials' => $this->code_materials,
            'stock' => $this->stock,
            'stock_min' => $this->stock_min,
            'description' => $this->description,
            'cost' => $this->cost,
            'url_evidence' => $this->url_evidence,
            'url_invoice' => $this->url_invoice,
            'provider_id' => $this->provider_id,
            'unit_type_id' => $this->unit_type_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'provider' => $this->provider ? [
                'id' => $this->provider->id,
                'name' => $this->provider->name,
            ] : null,
            'unity' => $this->unity ? [
                'id' => $this->unity->id,
                'name' => $this->unity->name,
                'abbreviation' => $this->unity->abbreviation,
            ] : null,
        ];
    }
}
