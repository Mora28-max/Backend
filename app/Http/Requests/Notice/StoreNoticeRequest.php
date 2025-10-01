<?php

namespace App\Http\Requests\Notice;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoticeRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'months_behind' => 'required|integer',
            'amount' => 'required|numeric',
            'comment' => 'sometimes|required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'El usuario es obligatorio.',
            'tracking_folio.required' => 'El folio de seguimiento es obligatorio.',
            'months_behind.required' => 'Los meses rezagados son obligatorios.',
            'comment.required' => 'El comentario es obligatorio.',
            'customer_id.exists' => 'El usuario no existe.',
            'months_behind.integer' => 'Los meses rezagados deben ser enteros.',
        ];
    }
}
