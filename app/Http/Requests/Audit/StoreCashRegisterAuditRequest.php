<?php

namespace App\Http\Requests\Audit;

use Illuminate\Foundation\Http\FormRequest;

class StoreCashRegisterAuditRequest extends FormRequest
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
            'receiver_user_id' => 'required|exists:users,id',
            'witness_user_id' => 'required|exists:users,id',
            'counted_cash' => 'required|numeric',
            'notes' => 'nullable|string',
            'started_at' => 'required|date',
            'ended_at' => 'required|date',
            'details' => 'required|json',
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_user_id.required' => 'El campo de usuario receptor es obligatorio.',
            'receiver_user_id.exists' => 'El usuario receptor seleccionado no es válido.',
            'witness_user_id.required' => 'El campo de usuario testigo es obligatorio.',
            'witness_user_id.exists' => 'El usuario testigo seleccionado no es válido.',
            'counted_cash.required' => 'El campo de efectivo contado es obligatorio.',
            'counted_cash.numeric' => 'El efectivo contado debe ser un número.',
            'notes.string' => 'El campo de notas debe ser una cadena de texto.',
            'started_at.required' => 'El campo de fecha de inicio es obligatorio.',
            'started_at.date' => 'El campo de fecha de inicio debe ser una fecha válida.',
            'ended_at.required' => 'El campo de fecha de finalización es obligatorio.',
            'ended_at.date' => 'El campo de fecha de finalización debe ser una fecha válida.',
            'details.json' => 'El campo de detalles debe ser una cadena JSON válida.',
        ];
    }
}
