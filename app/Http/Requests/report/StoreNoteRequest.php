<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'description' => 'sometimes|required_without:evidence|string|max:512',
            'evidence' => 'sometimes|file|required_without:description|mimes:jpg,jpeg,png,pdf,mp4,avi,mov|max:20480',
            'type_evidence' => 'required_if:evidence,required|in:photo,video,document',
            'tracking_folio' => 'required|string|exists:reports,tracking_folio',
        ];
    }

    public function messages(): array
    {
        return [
            //
            'description.required' => 'La descripción es obligatoria.',
            'description.max' => 'La descripción debe tener un máximo de 255 caracteres.',
            'evidence.required' => 'La evidencia es obligatoria.',
            'evidence.mimes' => 'La evidencia debe ser una imagen (jpg, jpeg, png), un PDF o un video (mp4, avi, mov).',
            'type_evidence.required' => 'El formato de la evidencia es obligatorio.',
            'type_evidence.in' => 'El formato de la evidencia debe ser una foto, un video o un documento.',
            'tracking_folio.required' => 'El folio de seguimiento es obligatorio.',
            'tracking_folio.exists' => 'El folio de seguimiento seleccionado no existe.',
        ];
    }
}
