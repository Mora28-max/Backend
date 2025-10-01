<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdditionalPaymentRequest extends FormRequest
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
            'notes' => 'sometimes|required|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'payment_type_id.required' => 'El tipo de cobro es obligatorio.',
            'payment_type_id.exists' => 'El tipo de cobro seleccionado no es válido.',
            'notes.required' => 'La nota es obligatoria.',
        ];
    }
}
