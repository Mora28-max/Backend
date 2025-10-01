<?php

namespace App\Http\Requests\CustomerView;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'phone' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'El id es obligatorío',
            'customer_id.exists' => 'Usuario no encontrado',
            'phone.required' => 'El número de teléfono es obligatorio',
            'phone.string' => 'El número de teléfono debe ser una cadena',
        ];
    }
}
