<?php

namespace App\Http\Requests\Confirmaiton;

use Illuminate\Foundation\Http\FormRequest;

class StoreConfirmationRequest extends FormRequest
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
            'item_id' => 'required|exists:items,id',
        ];
    }
}
