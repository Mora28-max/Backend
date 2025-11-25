<?php

namespace App\Http\Requests\Inventory;


use Illuminate\Foundation\Http\FormRequest;

class StoreProviderRequest extends FormRequest
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

            'name' => 'required|string|max:255',
            'rfc' => 'required|string|max:50|unique:providers',
            'person_type_id' => 'required|exists:person_types,id',
            'status_id' => 'required|exists:statuses,id',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'url_evidence' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'id_type' => 'required|integer|in:0,1',
        ];
    }

    /**
     * Mensajes personalizados (opcional, pero recomendable).
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del proveedor es obligatorio.',
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.unique' => 'Este RFC ya está registrado.',
            'id_type.required' => 'Debes seleccionar el tipo de proveedor.',
            'id_type.in' => 'El tipo de proveedor no es válido (usa 0 o 1).',
        ];
    }
}

      

