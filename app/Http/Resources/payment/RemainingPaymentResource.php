<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use App\Helpers\FormQuantity;
use Illuminate\Http\Resources\Json\JsonResource;

class RemainingPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $payment_type = $this->payment_type_id ? [
            'id' => $this->paymentType->id,
            'name' => $this->paymentType->name
        ] : null;

        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer' => $this->customer->fullName,
            'address' => $this->customer->address,
            'use_of_type' => $this->customer->useOfType->name,
            'classification' => $this->customer->classificationType->name,
            'tracking_folio' => $this->tracking_folio,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'vat' => $this->vat,
            'total' => $this->total,
            'total_text' => FormQuantity::convertFloatToText((float)$this->total),
            'payment_folio' => $this->payment_folio,
            'payment_type' => $payment_type,
            'breakdown' => $this->breakdown,
            'canceled' => $this->canceled,
            'note' => $this->note,
            'monthly_payment_statement' => $this->monthlyPaymentStatement->name,
            'created_at' => $this->created_at,
            'created_by' => 'Creado automáticamente por el sistema'
        ];
    }
}
