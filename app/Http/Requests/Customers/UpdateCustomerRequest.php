<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Customers\StoreCustomerRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        if (empty($this->all())) {
            $this->merge(['_empty' => true]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required_if:type_person,1|string|max:255',
            'phone' => 'sometimes|required|string|max:10',
            'email' => 'sometimes|required|string|email|max:255|unique:users',
            'rfc' => 'sometimes|required|string|max:255',
            'voter_key' => 'sometimes|required_if:type_person,1|string|max:255',
            'type_person' => 'sometimes|required',
            'address' => 'sometimes|required|string|max:255',
            'int_num' => 'sometimes|required|string',
            'ext_num' => 'sometimes|required|string',
            'zone_id' => 'sometimes|required|exists:zones,id',
            'geolocation' => 'sometimes|required|string|max:255',
            'reference' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|required|mimes:jpeg,png,jpg',
            'customer_type_id' => 'sometimes|required|exists:customer_types,id',
            'use_of_type_id' => 'sometimes|required|exists:use_of_types,id',
            'service_type_id' => 'sometimes|required|exists:service_types,id',
            'service_status_id' => 'sometimes|required|exists:service_status,id',
            'classification_type_id' => 'sometimes|required|exists:classification_types,id',
            'drainage_use' => 'sometimes|required|boolean',
            'additional_observation_id' => 'sometimes|required|exists:additional_observations,id',
            'meter' => 'sometimes|required|string',
            'installation_date' => 'required_with:meter',
            'storage_capacity' => 'sometimes|required',
        ];
    }

    public function messages(): array
    {
        return (new StoreCustomerRequest())->messages();
    }
}
