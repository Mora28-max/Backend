<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlyServiceChargeByUserResource extends JsonResource
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
            'year' => $this->year,
            'month' => $this->month,
            'water_amount' => (float) $this->water_amount,
            'drainage_amount' => (float) $this->drainage_amount,
            'overdue_month_number' => (float) $this->overdue_months,
            'water_surcharge' => (float) $this->water_surcharge,
            'drainage_surcharge' => (float) $this->drainage_surcharge,
            'water_discount' => (float) $this->water_discount,
            'water_surcharge_discount' => (float) $this->water_surcharge_discount,
            'drainage_discount' => (float) $this->drainage_discount,
            'drainage_surcharge_discount' => (float) $this->drainage_surcharge_discount,
            'water_amount_subtotal' => (float) $this->water_amount_subtotal,
            'drainage_amount_subtotal' => (float) $this->drainage_amount_subtotal,
            'vat' => (float) $this->vat,
            'total_amount' => (float) $this->total_amount,
        ];
    }
}
