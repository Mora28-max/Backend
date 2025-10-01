<?php

namespace App\Http\Resources\CustomerView;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WaterReceiptResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'month' => $this->month,
            'year' => $this->year,
            'total_amount' => $this->total_amount,
            'monthly_payment_statement' => $this->monthlyPaymentStatement->name,
        ];
    }
}
