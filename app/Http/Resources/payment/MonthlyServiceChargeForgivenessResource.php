<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlyServiceChargeForgivenessResource extends JsonResource
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
            "customer_id" => $this->customer_id,
            "year" => $this->year,
            "month" => $this->month,
            "overdue_months" => $this->overdue_months,
            "total_amount" => $this->total_amount,
            "monthly_payment_statement_id" => $this->monthly_payment_statement_id,
        ];
    }
}
