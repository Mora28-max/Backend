<?php

namespace App\Http\Requests\Reading;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReadingRequest extends FormRequest
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
            'new_reading' => 'required|numeric',
            'month' => 'required|integer',
            'year' => 'required|integer'
        ];
    }
    public function messages(): array
    {
        return [
            'month.required' => 'El mes es obligatorio.',
            'year.required' => 'El año es obligatorio.',
            'month.numeric' => 'El mes debe ser numérico.',
            'year.numeric' => 'El año debe ser numérico.',
            'new_reading.required' => 'El nuevo valor es obligatorio.',
            'new_reading.numeric' => 'El nuevo valor debe ser numérico.'
        ];
    }
}
