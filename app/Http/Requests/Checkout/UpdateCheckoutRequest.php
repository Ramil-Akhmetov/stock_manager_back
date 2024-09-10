<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCheckoutRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'note' => 'nullable|string',
            'customer_id' => 'sometimes|integer|exists:customers,id',

            'item_ids' => 'sometimes|array|min:1',
            'item_ids.*' => 'integer|exists:items,id',
        ];
    }
}
