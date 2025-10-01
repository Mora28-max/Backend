<?php

namespace App\Http\Requests\Locations\Zone;

use Illuminate\Foundation\Http\FormRequest;

class StoreZoneRequest extends FormRequest
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
            'colony_id' => 'required|exists:colonies,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre de la zona es obligatorio.',
            'name.string' => 'El nombre de la zona debe ser una cadena de caracteres.',
            'name.max' => 'El nombre de la zona no puede tener más de 255 caracteres.',
            'colony_id.required' => 'El campo colonia es obligatorio.',
            'colony_id.exists' => 'La colonia seleccionada no es válida.',
        ];
    }
}
