<?php

namespace App\Http\Requests\Catalogs;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Catalogs\StoreAdditionalObservationResquest;

class UpdateAdditionalObservationResquest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return (new StoreAdditionalObservationResquest())->rules();
    }

    public function messages(): array
    {
        return (new StoreAdditionalObservationResquest())->messages();
    }
}
