<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
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
            'name' => 'required|string',
            'number' => 'required|integer',
            'user_id' => 'sometimes|integer|exists:users,id',
            'room_type_id' => 'required|integer|exists:room_types,id',
            'racks' => 'nullable|array',
            'racks.*.id' => 'required_if:racks,number', //required if racks is not null
            'racks.*.name' => 'required_if:racks,null|string', //required if racks is not null
        ];
    }
}
