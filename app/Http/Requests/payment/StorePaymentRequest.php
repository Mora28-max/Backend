<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            "id_months_to_pay" => 'required|array|min:1',
            'payment_type_id' => 'required|exists:payment_types,id',
            'notes' => 'sometimes|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            //
            'customer_id.required' => 'El usuario es obligatorio.',
            'customer_id.exists' => 'El usuario no existe.',
            'id_months_to_pay.required' => 'Las meses a pagar son obligatorias.',
            'id_months_to_pay.min' => 'Las meses a pagar deben tener al menos un mes seleccionado.',
            'payment_type_id.required' => 'El tipo de cobro es obligatorio.',
            'payment_type_id.exists' => 'El tipo de cobro no existe.',
        ];
    }
}
