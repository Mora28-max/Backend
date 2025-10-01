<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required_if:type_person,1|string|max:255|nullable',
            'phone' => 'sometimes|required|string|max:10',
            'email' => 'sometimes|required|string|email|max:255|unique:users',
            'rfc' => 'sometimes|required|string|max:255',
            'voter_key' => 'required|max:255',
            'type_person' => 'required',
            'address' => 'required|string|max:255',
            'int_num' => 'sometimes|required|string',
            'ext_num' => 'sometimes|required|string',
            'zone_id' => 'required|exists:zones,id',
            'geolocation' => 'sometimes|required|string|max:255',
            'reference' => 'required|string|max:255',
            'image' => 'sometimes|required|mimes:jpeg,png,jpg',
            'customer_type_id' => 'required|exists:customer_types,id',
            'use_of_type_id' => 'required|exists:use_of_types,id',
            'service_type_id' => 'required|exists:service_types,id',
            'service_status_id' => 'prohibited',
            'classification_type_id' => 'required|exists:classification_types,id',
            'drainage_use' => 'required|boolean',
            'additional_observation_id' => 'sometimes|required|exists:additional_observations,id',
            'meter' => 'required|string',
            'installation_date' => 'required_with:meter',
            'storage_capacity' => 'sometimes|required',
        ];
    }

    public function messages(): array
    {
        return [
            //
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'last_name.required_if' => 'El apellido es obligatorio si es de tipo físico.',
            'address.required' => 'La dirección es obligatoria.',
            'zone_id.required' => 'El zona es obligatoria.',
            'customer_type_id.required' => 'El tipo de usuario es obligatorio.',
            'use_of_type_id.required' => 'El tipo de toma es obligatorio.',
            'service_type_id.required' => 'El tipo de servicio es obligatorio.',
            'service_status_id.required' => 'El estado del servicio es obligatorio.',
            'classification_type_id.required' => 'La clasificación es obligatoria.',
            'drainage_id.required' => 'El drenaje es obligatorio.',
            'image.required' => 'La imagen es obligatoria.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'phone.required' => 'El teléfono es obligatorio.',
            'meter.required' => 'El medidor es obligatorio.',
            'installation_date.required_if' => 'La fecha de instalación es requerida si el medidor está presente.',
            'additional_observation_id.required' => 'La observación es obligatoria.',
        ];
    }
}
