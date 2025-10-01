<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeterReadScheduleRequest extends FormRequest
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
            'montly_service_charge_id' => 'required|integer|exists:monthly_service_charges,id',
            'customer_id' => 'required|exists:customers,id',
        ];
    }

    public function messages(): array
    {
        return [
            'montly_service_charge_id.required' => 'El id del mes de lectura es obligatorio.',
            'customer_id.required' => 'El id del usuario es obligatorio.',
        ];
    }
}
