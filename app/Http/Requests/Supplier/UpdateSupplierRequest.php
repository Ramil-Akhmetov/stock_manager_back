<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateSupplierRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(Request $request): array
    {
        $supplier = $request->route('supplier');
        return [
            'name' => 'sometimes|string',
            'surname' => 'sometimes|string',
            'patronymic' => 'sometimes|string',
            'phone' => 'sometimes|string|unique:suppliers,phone,' . $supplier->id,
            'email' => 'sometimes|string|email|unique:suppliers,email,' . $supplier->id,
            'company' => 'sometimes|string',
        ];
    }
}
