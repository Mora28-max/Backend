<?php

namespace App\Http\Resources\Maintenance;

use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceHistoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'date' => $this->date,
            'observations' => $this->observations,
            'cost' => $this->cost,
            'next_maintenance_date' => $this->next_maintenance_date,
            'type_maintenance_id' => $this->id_type_maintenance,
            'goods_id' => $this->id_goods,
            'user_id' => $this->id_user,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relaciones
            'typeMaintenance' => $this->whenLoaded('typeMaintenance', function () {
                return [
                    'id' => $this->typeMaintenance->id,
                    'name' => $this->typeMaintenance->name,
                ];
            }),
            'goods' => $this->whenLoaded('goods', function () {
                return [
                    'id' => $this->goods->id,
                    'code_goods' => $this->goods->code_goods,
                    'name' => $this->goods->name,
                ];
            }),
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'firstname' => $this->user->firstname,
                ];
            }),
        ];
    }
}
