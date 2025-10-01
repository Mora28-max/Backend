<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Customers\StoreBeneficiaryRequest;

class UpdateClientBackupContactRequest extends FormRequest
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
        return (new StoreClientBackupContactRequest())->rules();
    }

    public function messages()
    {
        return (new StoreClientBackupContactRequest())->messages();
    }
}
