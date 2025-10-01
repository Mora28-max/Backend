<?php

namespace App\Http\Requests\WaterTank;

use Illuminate\Foundation\Http\FormRequest;

class StoreWaterTankRequest extends FormRequest
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
            'water_tank_id' => 'required|exists:water_tank,id',
            'water_level' => 'required|numeric|min:0',
            'water_reception' => 'required|numeric|min:0',
            'log_date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'water_tank_id.required' => 'El ID del tanque de agua es obligatorio.',
            'water_tank_id.exists' => 'El ID del tanque de agua debe existir.',
            'water_level.min' => 'El nivel de agua debe ser minimo de 0.',
            'water_reception.min' => 'La recepción de agua debe ser minimo de 0.',
            'water_level.required' => 'El nivel de agua es obligatorio.',
            'water_level.numeric' => 'El nivel de agua debe ser un número.',
            'water_reception.required' => 'La recepción de agua es obligatoria.',
            'water_reception.numeric' => 'La recepción de agua debe ser un número.',
            'log_date.required' => 'La fecha del registro es obligatoria.',
            'log_date.date' => 'La fecha del registro debe ser una fecha válida.',
        ];
    }
}
