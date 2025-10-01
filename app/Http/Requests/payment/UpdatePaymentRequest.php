<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
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
            'payment_type_id' => 'required|exists:payment_types,id',
            'notes' => 'sometimes|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'payment_type_id.required' => 'El tipo de pago es obligatorio.',
            'payment_type_id.exists' => 'El tipo de pago seleccionado no existe.',
            'notes.sometimes' => 'Las notas son opcionales.',
            'notes.string' => 'Las notas deben ser un texto.',
            'notes.max' => 'Las notas no pueden tener más de 255 caracteres.',
        ];
    }
}
