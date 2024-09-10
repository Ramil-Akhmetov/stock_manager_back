<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UpdateCustomerRequest extends FormRequest
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
        $customer = $request->route('customer');
        return [
            'name' => 'sometimes|string',
            'surname' => 'sometimes|string',
            'patronymic' => 'sometimes|string',
            'phone' => 'sometimes|string|unique:customers,phone,' . $customer->id,
            'email' => 'sometimes|string|email|unique:customers,email,' . $customer->id,
        ];
    }
}
