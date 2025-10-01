<?php

namespace App\Http\Resources\Agreement;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgreementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $payment_dates = json_decode($this->payment_breakdown);
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer' => $this->customer->fullName,
            'tracking_folio' => $this->tracking_folio,
            'initial_payment_amount' => $this->initial_payment_amount,
            'payment_days' => array_map(fn($date) => Carbon::parse($date->payment_day)->format('Y-m-d H:i:s'), $payment_dates),
            'total_debt' => $this->total_debt,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
