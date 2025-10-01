<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
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
            'customer_id' => 'required_without_all:name,client_backup_contacts_id|nullable|exists:customers,id',
            'client_backup_contacts_id' => 'required_without_all:customer_id,name|nullable|exists:client_backup_contacts,id',
            'phone' => 'required|string|max:12',
            'name' => 'required_without_all:customer_id,client_backup_contacts_id|string|max:255',
            'address' => 'required_without_all:customer_id,client_backup_contacts_id|string|max:255',
            'report_category_id' => 'required|exists:report_categories,id',
            'report_subcategory_id' => 'required|exists:report_subcategories,id',
            'report_priority_id' => 'required|exists:report_priorities,id',
            'report_child_subcategory_id' => 'required|exists:report_child_subcategories,id',
            'process_status_id' => 'sometimes|required|exists:process_status,id',
            'description' => 'required|string|max:500',
            'images_for_pdf' => 'sometimes|required',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'El id del usuario es obligatorio.',
            'customer_id.exists' => 'El id del usuario no existe.',
            'client_backup_contacts_id.required' => 'El beneficiario es obligatorio.',
            'client_backup_contacts_id.exists' => 'El beneficiario no existe.',
            'phone.required' => 'El teléfono es obligatorio.',
            'name.required' => 'El nombre es obligatorio.',
            'address.required' => 'El campo address es obligatorio.',
            'report_category_id.required' => 'La categoria es obligatoria.',
            'report_category_id.exists' => 'La categoria no existe.',
            'report_subcategory_id.required' => 'La subcategoria es obligatoria.',
            'report_subcategory_id.exists' => 'La subcategoria no existe.',
            'report_child_subcategory_id.required' => 'La subcategoria secundaria es obligatoria.',
            'report_child_subcategory_id.exists' => 'La subcategoria secundaria no existe.',
            'report_priority_id.required' => 'La prioridad es obligatoria.',
            'report_priority_id.exists' => 'La prioridad no existe.',
            'description.required' => 'El campo description es obligatorio.',
            'breakdown.required' => 'El campo breakdown es obligatorio.',
            'breakdown.string' => 'El campo breakdown debe ser una cadena de caracteres.',
            'should_be_paid.required' => 'Definir si el reporte debe ser pagado.',
            'should_be_paid.boolean' => 'Para definir si el reporte debe ser pagado, debe proporcionar un valor verdadero o falso.',
            'images_for_pdf.required' => 'Las imágenes para el PDF son obligatorias.',
            'process_status_id.required' => 'El estado del reporte es obligatorio.',
            'process_status_id.exists' => 'El estado del reporte no existe.',
        ];
    }
}
