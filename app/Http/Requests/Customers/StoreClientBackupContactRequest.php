<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientBackupContactRequest extends FormRequest
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
            'phone' => 'required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'kind' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'El id del usuario es obligatorio.',
            'phone.required' => 'El teléfono es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'firstname.required' => 'El nombre es obligatorio.',
            'lastname.required' => 'El apellido es obligatorio.',
            'kind.required' => 'El tipo de parentesco es obligatorio.',
        ];
    }
}
