<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PayRemainingPaymentRequest extends FormRequest
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
            'id' => 'required|exists:remaining_payments,id',
            'payment_type_id' => 'required|exists:payment_types,id',
            'type_remaining' => 'required|in:rep,notif,excess,extra',
            'note' => 'sometimes|required|string|max:255',
        ];
    }
    public function message(): array
    {
        return [
            'id.required' => 'El ID del adeudo es obligatorio.',
            'id.exists' => 'El adeudo seleccionado no existe.',
            'payment_type_id.required' => 'El tipo de cobro es obligatorio.',
            'payment_type_id.exists' => 'El tipo de cobro seleccionado no existe.',
            'type_remaining.required' => 'El tipo de operación es obligatorio.',
            'type_remaining.in' => "El tipo de operación seleccionado no es válido. Debe ser 'rep', 'notif', 'excess' o 'extra'.",
        ];
    }
}
