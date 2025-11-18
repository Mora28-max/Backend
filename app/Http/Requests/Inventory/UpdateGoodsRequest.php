<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGoodsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'brand' => 'nullable|string|max:100',
            'id_status' => 'sometimes|required|exists:goods_status,id',
            'stock' => 'sometimes|required|integer|min:0',
            'id_category' => 'sometimes|required|exists:categories,id',
            'provider_id' => 'sometimes|required|exists:providers,id', // se mapeará a id_provider en el controller
            'code_goods' => 'nullable|string|max:50',
            'url_evidence' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del bien es obligatorio.',
            'id_status.required' => 'Debe seleccionar un estado del bien.',
            'id_category.required' => 'Debe seleccionar una categoría.',
            'provider_id.required' => 'Debe seleccionar un proveedor.',
        ];
    }
}
