<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class DeleteInventoryMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // No necesitamos reglas, porque el ID viene por la URL
    public function rules(): array
    {
        return [];
    }
}
