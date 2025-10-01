<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
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
            '_empty' => 'prohibited',
            'customer_id' => 'sometimes|required_without_all:name,client_backup_contacts_id|nullable|exists:customers,id',
            'client_backup_contacts_id' => 'sometimes|required_without_all:customer_id,name|nullable|exists:client_backup_contacts,id',
            'phone' => 'sometimes|required|string|max:12',
            'name' => 'sometimes|required_without_all:customer_id,client_backup_contacts_id|string|max:255',
            'address' => 'sometimes|required_without_all:customer_id,client_backup_contacts_id|string|max:255',
            'report_category_id' => 'sometimes|exists:report_categories,id',
            'report_subcategory_id' => 'sometimes|exists:report_subcategories,id',
            'report_priority_id' => 'sometimes|exists:report_priorities,id',
            'report_child_subcategory_id' => 'sometimes|exists:report_child_subcategories,id',
            'process_status_id' => 'sometimes|exists:process_status,id',
            'description' => 'sometimes|required|string|max:500',
            'supervision_user' => 'sometimes|exists:users,id',
            'breakdown' => 'sometimes|required|string',
            'images_for_pdf' => 'sometimes|required',
            'subtotal' => 'required_with:breakdown|numeric|min:0',
            'vat' => 'required_with:breakdown|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return array_merge(
            (new StoreReportRequest())->messages(),
            [
                'subtotal.required_with' => 'El subtotal es obligatorio cuando se proporciona el desglose.',
                'vat.required_with' => 'El IVA es obligatorio cuando se proporciona el desglose.',
            ]
        );
    }
}
