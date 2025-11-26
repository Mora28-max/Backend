<?php

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
       return [
        'date' => 'required|date',
        'id_goods' => 'required|exists:goods,id',
        'id_type_maintenance' => 'required|exists:type_maintenances,id',
        'id_user' => 'sometimes|exists:users,id',
        'observations' => 'sometimes|string',
        'cost' => 'nullable|numeric|min:0',
        'next_maintenance_date' => 'sometimes|date|after_or_equal:date',
        'code' => 'nullable|string|max:255',
    ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'La fecha del mantenimiento es obligatoria.',
            'id_goods.required' => 'Debes seleccionar un bien.',
            'id_goods.exists' => 'El bien seleccionado no existe.',
            'id_type_maintenance.required' => 'Debes seleccionar un tipo de mantenimiento.',
            'id_type_maintenance.exists' => 'El tipo de mantenimiento no existe.',
            'id_user.required' => 'Debes asignar un usuario responsable.',
            'id_user.exists' => 'El usuario no existe.',
            'next_maintenance_date.after_or_equal' => 'La próxima fecha debe ser igual o posterior a la fecha del mantenimiento.'
        ];
    }
}
