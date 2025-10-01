<?php

namespace App\Http\Requests\Agreement;

use Illuminate\Foundation\Http\FormRequest;

class CalculateAgreementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            //
            'customer_id' => 'required|exists:customers,id',
            'payment_breakdown' => 'required|json',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'El id del usuario es obligatorio.',
            'customer_id.exists' => 'El usuario buscado no existe.',
            'payment_breakdown.required' => 'Los detalles de pago son obligatorios.',
            'payment_breakdown.string' => 'Los detalles de pago deben ser una cadena de caracteres.',
        ];
    }
}
