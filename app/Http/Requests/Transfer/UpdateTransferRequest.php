<?php

namespace App\Http\Requests\Transfer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransferRequest extends FormRequest
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
            'room_id' => 'sometimes|integer|exists:rooms,id',

            'items' => 'sometimes|array|min:1',
            'items.*.id' => 'required|integer|exists:items,id',
            'items.*.room_id' => 'required|integer|exists:rooms,id',
            'items.*.quantity' => 'nullable|numeric',
        ];
    }
}
