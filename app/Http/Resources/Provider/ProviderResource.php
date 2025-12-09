<?php

namespace App\Http\Resources\Provider;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
{
     /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        // Etiquetas para id_type
        $typeLabels = [
            0 => 'Proveedor de bienes',
            1 => 'Proveedor de materiales',
        ];

        return [
            'id' => $this->id,
            'code_provider' => $this->code_provider,
            'provider_key' => $this->provider_key,
            'name' => $this->name,
            'rfc' => $this->rfc,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'url_evidence' => $this->url_evidence,
            'id_user' => $this->id_user,
            'id_type' => $this->id_type,
            'type_label' => $typeLabels[$this->id_type] ?? null, // Nombre legible
            'public_id_evidence' => $this->public_id_evidence,   // Para manejo de Cloudinary
            'person_type' => $this->personType
                ? [
                    'id' => $this->personType->id,
                    'name' => $this->personType->name
                ]
                : null,
            'status' => $this->status
                ? [
                    'id' => $this->status->id,
                    'name' => $this->status->name
                ]
                : null,
            'user' => $this->user
                ? [
                    'id' => $this->user->id,
                    'firstname' => $this->user->firstname
                ]
                : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}