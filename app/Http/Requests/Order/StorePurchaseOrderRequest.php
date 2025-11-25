<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir siempre
    }

    public function rules(): array
    {
        return [
            'provider_id' => 'required|exists:providers,id',
            'area_requester' => 'nullable|string',

            'delivery_date' => 'nullable|date',

            'items' => 'required|array|min:1',
            'items.*.inventory_material_id' => 'required|exists:inventory_materials,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'provider_id.required' => 'El proveedor es obligatorio.',
            'items.required' => 'Debe agregar al menos un material.',
            'items.*.inventory_material_id.exists' => 'Uno de los materiales no existe en inventario.',
        ];
    }
}
