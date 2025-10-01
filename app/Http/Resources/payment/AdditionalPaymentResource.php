<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use App\Helpers\FormQuantity;
use Illuminate\Http\Resources\Json\JsonResource;

class AdditionalPaymentResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'total' => $this->total,
            'payment_type_id' => $this->paymentType->id,
            'payment_type_name' => $this->paymentType->name,
            'subtotal' => $this->subtotal,
            'vat' => $this->vat,
            'discount' => $this->discount,
            'concept' => $this->concept,
            'total' => $this->total,
            'total_text' => FormQuantity::convertFloatToText((float)$this->total),
            'payment_folio' => $this->payment_folio,
            'canceled' => $this->canceled,
            'note' => $this->note,
            'address' => $this->address,
            'created_by' => $this->user->fullName,
            'created_at' => $this->created_at,
        ];
    }
}
