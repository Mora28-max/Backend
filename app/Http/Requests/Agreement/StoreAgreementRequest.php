<?php

namespace App\Http\Requests\Agreement;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgreementRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'payment_breakdown' => 'required|json',
            'id_months_to_pay' => 'sometimes|required|array',
            'payment_type_id' => 'required_if:id_months_to_pay,exists:payment_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'El id del usuario es obligatorio.',
            'customer_id.exists' => 'El usuario buscado no existe.',
            'total_debt.required' => 'El campo total de deuda es obligatorio.',
            'total_debt.numeric' => 'El campo total de deuda debe ser numérico.',
            'payment_breakdown.required' => 'Los detalles de pago son obligatorios.',
            'payment_breakdown.string' => 'Los detalles de pago deben ser una cadena de caracteres.',
            'id_months_to_pay.required' => 'Los meses del pago inicial son obligatorios.',
            'payment_type_id.required' => 'El tipo de pago es obligatorio.',
            'payment_type_id.exists' => 'El tipo de pago seleccionado no es válido.',
        ];
    }
}
