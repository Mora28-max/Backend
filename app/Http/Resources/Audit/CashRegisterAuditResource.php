<?php

namespace App\Http\Resources\Audit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashRegisterAuditResource extends JsonResource
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
            'issued_by' => $this->user->fullName,
            'receiver' => $this->receiver->fullName,
            'witness' => $this->witness->fullName,
            'counted_cash' => $this->counted_cash,
            'system_cash' => $this->system_cash,
            'digital_total' => $this->digital_total,
            'discrepancy' => $this->discrepancy,
            'notes' => $this->notes,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'details' => $this->details,
            'digital_details' => $this->digital_details,
            'created_at' => $this->created_at,
        ];
    }
}
