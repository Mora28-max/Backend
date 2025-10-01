<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlyServiceChargeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'customer_id' => $this->customer_id,
            'customer' => $this->customer->fullName,
            'address' => $this->customer->address,
            'int_num' => $this->customer->int_num,
            'ext_num' => $this->customer->ext_num,
            'zone' => [
                'id' => $this->customer->zone->id,
                'name' => $this->customer->zone->name,
            ],
            'colony' => [
                'id' => $this->customer->zone->colony->id,
                'name' => $this->customer->zone->colony->name,
            ],
            'use_of_type' => [
                'id' => $this->customer->useOfType->id,
                'name' => $this->customer->useOfType->name,
            ],
            'additional_observation' => [
                'id' => $this->customer->additionalObservation->id,
                'description' => $this->customer->additionalObservation->description,
            ],
            'classification' => [
                'id' => $this->customer->classificationType->id,
                'name' => $this->customer->classificationType->name,
            ],
            'service_status' => [
                'id' => $this->customer->serviceStatus->id,
                'name' => $this->customer->serviceStatus->name,
            ],
            'water' => (float) $this->water_amount,
            'drainage' => (float) $this->drainage_amount,
            'iva' => (float) $this->vat,
            'general' => (float) $this->total_amount,
            'month' => $this->month,
            'year' => $this->year,
            'monthly_service_charge_id' => $this->monthly_service_charge_id,
            'max_overdue_months' => (int) $this->max_overdue_months ?? 0,
            'subtotal_water' => (float) $this->subtotal_water ?? 0,
            'subtotal_drainage' => (float) $this->subtotal_drainage ?? 0,
            'subtotal_vat' => (float) $this->subtotal_vat ?? 0,
            'total_general' => (float) $this->total_general ?? 0
        ];
    }
}
