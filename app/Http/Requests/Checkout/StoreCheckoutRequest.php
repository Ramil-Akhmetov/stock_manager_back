<?php

namespace App\Http\Requests\Checkout;

use App\Models\Item;
use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;

class StoreCheckoutRequest extends FormRequest
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
            'note' => 'required|string',

            'room_id' => 'required|integer|exists:rooms,id',

            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:items,id',
            'items.*.fullCheckout' => 'nullable|boolean',

            'items.*.quantity' => 'nullable|numeric|min:1'
        ];
    }


    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator($validator)
    {
        $items = $this->input('items') ?? [];

        foreach ($items as $index => $item) {
            $validator->sometimes("items.$index.quantity", 'required', function () use ($item) {
                return isset($item['fullCheckout']) && $item['fullCheckout'] === false;
            });
        }

        $validator->after(function ($validator) use ($items) {
            foreach ($items as $index => $item) {
                if (isset($item['fullCheckout']) && $item['fullCheckout'] === false) {
                    $itemId = $item['id'];
                    $quantity = $item['quantity'] ?? 0;
                    $existingItem = Item::find($itemId);
                    if ($existingItem && $quantity >= $existingItem->quantity) {
                        $validator->errors()->add("items.$index.quantity", "The quantity cannot be greater or equal than the existing quantity of {$existingItem->quantity}.");
                    }
                }
            }
        });
    }
}
