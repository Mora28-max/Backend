<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class GoodsResource extends JsonResource
{
    /**
     * Transformar el recurso en un array.
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'brand' => $this->brand,
            'stock' => $this->stock,
            'code_goods' => $this->code_goods,
            'id_status' => $this->id_status,
            'id_category' => $this->id_category,
            'id_provider' => $this->id_provider,
            'id_user' => $this->id_user,
            'url_evidence' => $this->url_evidence,
            'url_invoice' => $this->url_invoice,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones
            'status' => $this->whenLoaded('status', function() {
                return $this->status ? [
                    'id' => $this->status->id,
                    'name' => $this->status->name,
                ] : null;
            }),

            'category' => $this->whenLoaded('category', function() {
                return $this->category ? [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ] : null;
            }),

            'provider' => $this->whenLoaded('provider', function() {
                return $this->provider ? [
                    'id' => $this->provider->id,
                    'name' => $this->provider->name,
                ] : null;
            }),
        ];
    }
}
