<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeterReadScheduleRequest extends FormRequest
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
            'evidence' => 'sometimes|required|mimes:jpeg,png,jpg',
            'meter_reading' => 'required|numeric',
            '_method' => 'required'
        ];
    }
    public function messages(): array
    {
        return [
            'evidence.required' => 'La evidencia es obligatoria.',
            'evidence.mimes' => 'La evidencia debe ser una imagen en formato jpeg, png o jpg.',
            'meter_reading.required' => 'El valor de la lectura es obligatorio.',
            'meter_reading.numeric' => 'El valor de la lectura debe ser un número.',
            '_method.required' => 'El método es obligatorio.',
        ];
    }
}
