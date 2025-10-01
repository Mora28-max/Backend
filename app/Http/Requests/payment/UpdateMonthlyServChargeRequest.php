<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Pest\Mutate\Mutators\Logical\InstanceOfToTrue;

class UpdateMonthlyServChargeRequest extends FormRequest
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
            'operation_name' => 'required|exists:amount_modification_operations,name',
            'percentage_discount' => 'required_unless:operation_name,SURCHARGE,FORGIVE_AMOUNTS,RESET,REVERSE_FORGIVENESS|numeric|min:0|max:100',
            'months_to_be_updated' => 'required_unless:operation_name,SURCHARGE|array',
        ];
    }

    public function messages(): array
    {
        return [
            //
            'operation_name.required' => 'El nombre de la operación es obligatorio.',
            'operation_name.exists' => 'La operación seleccionada no existe.',
            'percentage_discount.required' => 'El porcentaje es obligatorio.',
            'percentage_discount.numeric' => 'El  porcentaje debe ser numérico.',
            'percentage_discount.min' => 'El  porcentaje debe ser mayor o igual a 0.',
            'percentage_discount.max' => 'El  porcentaje debe ser menor o igual a 100.',
            'percentage_discount.required_unless' => 'El porcentaje es obligatorio si la operación no es recargos o condonación.',
            'months_to_be_updated.required_unless' => 'Los meses a actualizar son obligatorios si la operación no es recargos o condonación.',
        ];
    }
}
