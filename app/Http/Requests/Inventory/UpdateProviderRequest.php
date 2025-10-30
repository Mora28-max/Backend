<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProviderRequest extends FormRequest
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
        $id = $this->route('id'); // obtiene el id del proveedor
        return [
                
            'name' => 'sometimes|required|string|max:255',
            'rfc' => 'sometimes|required|string|max:50|unique:providers,rfc,' . $this->route('id'),
            'person_type_id' => 'sometimes|required|exists:person_types,id',
            'status_id' => 'sometimes|required|exists:statuses,id',
            'address' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:255',
            'url_evidence' => 'nullable|string|max:255',
            'id_type' => 'sometimes|required|integer|in:0,1',
        ];
    }
}
