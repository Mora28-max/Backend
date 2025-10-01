<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class VerifyStatusRequest extends FormRequest
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
            'tracking_folio' => 'required|string|exists:reports,tracking_folio',
            'report_category_id' => 'required|integer|exists:report_categories,id',
            'report_subcategory_id' => 'required|integer|exists:report_subcategories,id',
            'report_child_subcategory_id' => 'nullable|integer|exists:report_child_subcategories,id',
        ];
    }
}
