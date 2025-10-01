<?php

namespace App\Http\Resources\Schedule;

use App\Traits\HasDefaultImage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeterReadingScheduleResource extends JsonResource
{
    use HasDefaultImage, HasDefaultImage;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'montly_service_charge_id' => $this->montlyServiceCharge->id,
            'user' => $this->user->fullName,
            'evidence' => $this->getImageUrl($this->evidence),
            'customer' => $this->montlyServiceCharge->customer->fullName,
            'customer_id' => $this->montlyServiceCharge->customer_id,
            'address' => $this->montlyServiceCharge->customer->address,
            'zone' => $this->montlyServiceCharge->customer->zone->name,
            'colony' => $this->montlyServiceCharge->customer->zone->colony->name,
        ];
    }
}
