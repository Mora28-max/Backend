<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AssignRoleRequest extends FormRequest
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
            'password' => 'required',
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'La contraseña es obligatoria.',
            'user_id.required' => 'Debes seleccionar un usuario.',
            'user_id.exists' => 'El usuario no existe.',
            'role.required' => 'El rol es obligatorio.',
            'role.exists' => 'El rol no existe.',
        ];
    }
}
