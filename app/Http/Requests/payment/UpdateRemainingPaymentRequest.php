<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Payment\StoreRemainingPaymentRequest;

class UpdateRemainingPaymentRequest extends FormRequest
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
            'subtotal' => 'sometimes|required|numeric|min:0',
            'vat' => 'sometimes|required|numeric|min:0',
            'discount' => 'sometimes|required|numeric|min:0',
            'breakdown' => 'sometimes|required|string',
            'note' => 'sometimes|required|string|max:255',
            'payment_type_id' => 'sometimes|required|exists:payment_types,id',
        ];
    }

    public function messages(): array
    {
        return (new StoreRemainingPaymentRequest())->messages();
    }
}
