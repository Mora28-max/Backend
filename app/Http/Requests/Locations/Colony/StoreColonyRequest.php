<?php

namespace App\Http\Requests\Locations\Colony;

use Illuminate\Foundation\Http\FormRequest;

class StoreColonyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
            'name' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la colonia es obligatorio.',
            'name.string' => 'El nombre de la colonia debe ser una cadena de texto.',
            'name.max' => 'El nombre de la colonia no puede tener más de 255 caracteres.',
        ];
    }
}
