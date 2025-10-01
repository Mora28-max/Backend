<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialUsedRequest extends FormRequest
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
            'quantity' => 'required|numeric|min:0',
            'unit_id' => 'required|exists:unities,id',
            'material_id' => 'required|exists:hydraulic_repair_materials,id',
            'report_id' => 'required|exists:reports,id',
        ];
    }

    public function messages(): array
    {
        return [
            //
            'quantity.required' => 'El campo de cantidad es obligatorio.',
            'quantity.numeric' => 'El campo de cantidad debe ser numérico.',
            'quantity.min' => 'El campo de cantidad debe ser mayor a 0.',
            'unit_id.required' => 'El campo de unidad es obligatorio.',
            'unit_id.exists' => 'El campo de unidad seleccionado no existe.',
            'material_id.required' => 'El campo de material es obligatorio.',
            'material_id.exists' => 'El campo de material seleccionado no existe.',
            'report_id.required' => 'El campo de reporte es obligatorio.',
            'report_id.exists' => 'El campo de reporte seleccionado no existe.',
        ];
    }
}
