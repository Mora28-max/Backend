<?php

namespace App\Http\Requests\Maintenance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'sometimes|date',
            'id_goods' => 'sometimes|exists:goods,id',
            'id_type_maintenance' => 'sometimes|exists:type_maintenances,id',
            'id_user' => 'sometimes|exists:users,id',
            'observations' => 'sometimes|string',
            'cost' => 'nullable|numeric|min:0',
            'next_maintenance_date' => 'nullable|date|after_or_equal:date',
            'code' => 'nullable|string|max:255',
        ];
    }
}
