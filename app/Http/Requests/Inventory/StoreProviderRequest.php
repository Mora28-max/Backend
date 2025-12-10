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
            'rfc' => 'nullable|string|max:50|unique:providers',
            'provider_key' => 'nullable|string|max:100',
            'person_type_id' => 'nullable|exists:person_types,id',
            'status_id' => 'nullable|exists:statuses,id',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'url_evidence' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'id_type' => 'nullable|integer|in:0,1',
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

      

