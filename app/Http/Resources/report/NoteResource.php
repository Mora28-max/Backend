<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Request;
use App\Helpers\UploadDataToCloudinary;
use App\Traits\HasDefaultImage;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{

    use HasDefaultImage;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $extension = strtolower($this->format_evidence);
        $map = [
            'jpg'  => 'image',
            'jpeg' => 'image',
            'png'  => 'image',
            'mp4'  => 'video',
            'pdf'  => 'image',
        ];

        $url = null;

        if (!empty($this->url_evidence)) {
            if (str_contains($this->url_evidence, 'soapamz-')) {
                $url = UploadDataToCloudinary::getSignedAuthenticatedUrl($this->url_evidence, $map[$extension] ?? 'image', $extension);
            } else {
                $prefix = explode('/', $this->url_evidence);
                $url = UploadDataToCloudinary::getFirstPublicImageUrlByPrefix($prefix[0], $prefix[1]);
            }
        }

        return [
            'id' => $this->id,
            'description' => $this->description,
            'url_evidence' => $url ?? null,
            'user' => $this->user->fullName ?? "Usuario no encontrado",
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
