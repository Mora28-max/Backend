<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryMaterialRequest extends FormRequest
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
            'stock' => 'required|integer',
            'description' => 'nullable|string',
            'cost' => 'required|numeric',
            'url_evidence' => 'nullable|string',
            'provider_id' => 'required|exists:providers,id',
            'unit_type_id' => 'required|exists:unities,id',
    
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del material es obligatorio.',
            'stock.required' => 'El stock es obligatorio.',
            'provider_id.exists' => 'El proveedor seleccionado no existe.',
            'unit_type_id.exists' => 'La unidad seleccionada no existe.',
        ];
    }
}
