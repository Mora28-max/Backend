<?php

namespace App\Http\Resources\ActivityLog;

use App\Helpers\FormatDate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    private $lang =  [
        'created' => 'creado',
        'updated' => 'actualizado',
        'deleted' => 'eliminado',
    ];

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->lang[$this->description] ?? $this->description,
            'causer' => $this->causer ? $this->causer->firstname . ' ' . $this->causer->lastname : null,
            'created_at' => FormatDate::fullDateTime($this->created_at),
            'properties' => [
                'attributes' => $this->description === 'created' ? ($this->properties['attributes'] ?? []) : [],
                'old' => $this->properties['old'] ?? [],
                'new' => $this->description === 'updated' ? ($this->properties['attributes'] ?? []) : [],
            ],
            'subject' => $this->subject_id ?? null,
            'log_name' => $this->log_name ?? null
        ];
    }
}
