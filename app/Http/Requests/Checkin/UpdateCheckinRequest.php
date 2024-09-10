<?php

namespace App\Http\Requests\Checkin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UpdateCheckinRequest extends FormRequest
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
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'room_id' => 'required|exists:rooms,id',

            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|numeric|exists:items,id',
            'items.*.rack_id' => 'required|exists:racks,id',
            'items.*.name' => 'required|string',
            'items.*.code' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $itemId = $this->input(str_replace('code', 'id', $attribute));
                    if (\App\Models\Item::where('code', $value)->where('id', '!=', $itemId)->exists()) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                },
            ],
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit' => 'required|string',
            'items.*.photo' => 'nullable|string',
            'items.*.category_id' => 'nullable|exists:categories,id',
            'items.*.type_id' => 'nullable|exists:types,id',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        // Custom validation to ensure each item's code is unique within the request payload
        $codes = collect($data['items'])->pluck('code');
        if ($codes->count() !== $codes->unique()->count()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Коды должны быть уникальными',
                    'errors' => [
                        'items.*.code' => [
                            'Коды должны быть уникальными',
                        ]
                    ]
                ], 422)
            );
        }

        return $data;
    }
}
