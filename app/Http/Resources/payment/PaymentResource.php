<?php

namespace App\Http\Resources\Payment;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\FormQuantity;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'customer' => $this->customer->fullName,
            'address' => $this->customer->address . ' ' . $this->customer->int_num,
            'use_of_type' => $this->customer->useOfType->name,
            'classification_type' => $this->customer->classificationType->name,
            'folio' => $this->folio,
            'water' => $this->water,
            'water_surcharge' => $this->water_surcharge,
            'water_discount' => $this->water_discount,
            'water_surcharge_discount' => $this->water_surcharge_discount,
            'drainage' => $this->drainage,
            'drainage_surcharge' => $this->drainage_surcharge,
            'drainage_discount' => $this->drainage_discount,
            'drainage_surcharge_discount' => $this->drainage_surcharge_discount,
            'vat' => $this->vat,
            'total' => $this->total,
            'total_text' => FormQuantity::convertFloatToText((float)$this->total),
            'payment_type_id' =>  $this->paymentType->id,
            'payment_type' =>  $this->paymentType->name,
            'payment_date' => $this->payment_date,
            'payment_ISO_date' => $this->payment_date ? Carbon::parse($this->payment_date)->toIso8601String() : null,
            'notes' => $this->notes,
            'period' => $this->period ?? [],
            'created_by' => $this->user->fullName,
            'canceled' => $this->canceled,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
