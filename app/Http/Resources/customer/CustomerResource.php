<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use App\Helpers\UploadDataToCloudinary;
use App\Traits\HasDefaultImage;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    use HasDefaultImage;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'folio' => $this->folio,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'rfc' => $this->rfc,
            'voter_key' => $this->voter_key,
            'type_person' => $this->readable_type_person,
            'address' => $this->address,
            'int_num' => $this->int_num,
            'ext_num' => $this->ext_num,
            'geolocation' => $this->geolocation,
            'reference' => $this->reference,
            'url_image' => $this->getImageUrl($this->url_image),
            'drainage_use' => $this->drainage_use,
            'additional_observation' => $this->additionalObservation,
            'storage_capacity' => $this->storage_capacity,
            'zone' => $this->zone,
            'customer_type' =>  $this->customerType,
            'use_of_type' => $this->useOfType,
            'service_type' => $this->serviceType,
            'service_status' => $this->serviceStatus,
            'classification_type' => $this->classificationType,
            'meter' => $this->meter,
        ];
    }
}
