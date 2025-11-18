<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInventoryMaterialRequest extends FormRequest
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
        'name' => 'sometimes|string|max:255',
        'stock' => 'sometimes|integer',
        'stock_min' => 'sometimes|integer|min:0',
        'description' => 'sometimes|string',
        'cost' => 'sometimes|numeric',
        'url_evidence' => 'sometimes|nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        'url_invoice' => 'sometimes|nullable|file|mimes:pdf,jpeg,png,jpg|max:4096',
        'provider_id' => 'sometimes|exists:providers,id',
        'unit_type_id' => 'sometimes|exists:unities,id',
        ];
    }
    
      public function messages(): array
    {
        return [
            'provider_id.exists' => 'El proveedor seleccionado no existe.',
            'unit_type_id.exists' => 'La unidad seleccionada no existe.',
        ];
    }
}
