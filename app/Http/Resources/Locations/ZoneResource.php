<?php

namespace App\Http\Resources\Locations;

use Illuminate\Http\Request;
use App\Http\Resources\Locations\ColonyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ZoneResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'colony' => new ColonyResource($this->colony),
        ];
    }
}
