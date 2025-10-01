<?php

namespace App\Http\Requests\Notice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoticeRequest extends FormRequest
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
            'process_status_id' => 'sometimes|required|exists:process_status,id',
            'notice_type_id' => 'sometimes|required|exists:notice_types,id',
            'comment' => 'sometimes|required|string',
            'evidence' => 'sometimes|required|file|mimes:png,jpg,jpeg',
        ];
    }

    public function messages(): array
    {
        return [
            'evidence.required' => 'La evidencia es obligatoria.',
            'comment.string' => 'El comentario debe ser una cadena.',
            'notice_type_id.required' => 'El tipo de aviso es obligatorio.',
            'notice_type_id.exists' => 'El tipo de notificación no existe.',
            'process_status_id.required' => 'El estado del proceso es obligatorio.',
            'process_status_id.exists' => 'El estado del proceso no existe.',
            'comment.required' => 'El comentario es obligatorio.',
            'evidence.string' => 'La evidencia debe ser una cadena.',
        ];
    }
}
