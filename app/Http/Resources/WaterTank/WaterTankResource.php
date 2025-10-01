<?php

namespace App\Http\Resources\WaterTank;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WaterTankResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "water_tank" => $this->waterTank->name,
            "water_level" => $this->water_level,
            "water_reception" => $this->water_reception,
            "log_date" => $this->log_date,
            "created_at" => $this->created_at,
        ];
    }
}
