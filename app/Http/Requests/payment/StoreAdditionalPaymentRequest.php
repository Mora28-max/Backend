<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdditionalPaymentRequest extends FormRequest
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
            'code' => 'sometimes|required|string|max:255',
            'name' => 'required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'concept' => 'required|string|max:255',
            'notes' => 'required_with:code|string|max:255',
            'subtotal' => 'required|numeric|min:0',
            'vat' => 'required|numeric|min:0',
            'payment_type_id' => 'required|exists:payment_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            //
            'code.required' => 'El codigo es obligatorio.',
            'name.required' => 'El nombre es obligatorio.',
            'address.required' => 'La dirección es obligatoria.',
            'concept.required' => 'El concepto es obligatorio.',
            'subtotal.required' => 'El subtotal es obligatorio.',
            'vat.required' => 'El iva es obligatorio.',
            'subtotal.min' => 'El monto debe ser mayor a 0.',
        ];
    }
}
