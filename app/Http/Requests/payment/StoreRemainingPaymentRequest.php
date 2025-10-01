<?php

namespace App\Http\Requests\Payment;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRemainingPaymentRequest extends FormRequest
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
            'tracking_folio' => 'required|string|max:255',
            'subtotal' => 'required|numeric|min:0',
            'vat' => 'sometimes|required|numeric|min:0',
            'breakdown' => 'sometimes|required|string',
            'note' => 'sometimes|required|string|max:255',
            'payment_type_id' => 'sometimes|required|exists:payment_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'The id del usuario es obligatorio.',
            'customer_id.exists' => 'El usuario buscado no existe.',
            'tracking_folio.required' => 'El folio de seguimiento es obligatorio.',
            'subtotal.required' => 'El subtotal es obligatorio.',
            'vat.required' => 'The iva es obligatorio.',
            'subtotal.min' => 'El monto debe ser mayor a 0.',
            'vat.min' => 'El monto de iva debe ser mayor a 0.',
            'tracking_folio.unique' => 'El folio de seguimiento ya ha sido registrado.',
        ];
    }
}
