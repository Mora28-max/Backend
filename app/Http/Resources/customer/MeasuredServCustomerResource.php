<?php

namespace App\Http\Resources\Customer;

use App\Helpers\FormatDate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeasuredServCustomerResource extends JsonResource
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
            'name' => $this->customer->fullName,
            'zone' => $this->customer->zone->name,
            'colony' => $this->customer->zone->colony->name,
            'month' => FormatDate::month($this->month),
            'year' => $this->year,
            'old_reading' => (float) $this->old_reading,
            'new_reading' => (float) $this->new_reading,
        ];
    }
}
