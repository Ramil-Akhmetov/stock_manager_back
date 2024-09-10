<?php

namespace App\Http\Requests\Transfer;

use App\Models\Item;
use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
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
            'reason' => 'required|string',
            'from_room_id' => 'required|integer|exists:rooms,id',
            'to_room_id' => 'required|integer|exists:rooms,id',

            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:items,id',
            'items.*.fullTransfer' => 'nullable|boolean',

            'items.*.to_rack_id' => 'nullable|exists:racks,id',
            'items.*.newCode' => 'nullable|string|unique:items,code',
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
        $toRoomId = $this->input('to_room_id');
        $toRoom = Room::find($toRoomId);

        foreach ($items as $index => $item) {
            $validator->sometimes("items.$index.to_rack_id", 'required', function () use ($item, $toRoom) {
                return $toRoom && $toRoom->room_type_id == 1;
            });

            $validator->sometimes("items.$index.newCode", 'required', function () use ($item) {
                return isset($item['fullTransfer']) && $item['fullTransfer'] === false;
            });

            $validator->sometimes("items.$index.quantity", 'required', function () use ($item) {
                return isset($item['fullTransfer']) && $item['fullTransfer'] === false;
            });
        }

        $validator->after(function ($validator) use ($items) {
            foreach ($items as $index => $item) {
                if (isset($item['fullTransfer']) && $item['fullTransfer'] === false) {
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
