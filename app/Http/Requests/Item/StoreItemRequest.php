<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
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
            'name' => 'required',
            'code' => 'required|unique:items,code',
            'quantity' => 'required_with:unit',
            'unit' => 'required_with:quantity',
            'photo' => 'nullable|image',
            'category_id' => 'nullable|exists:categories,id',
            'type_id' => 'nullable|exists:types,id',
            'room_id' => 'required|exists:rooms,id',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = $this->validator->validated();
        if ($this->has('photo') && $this->photo) {
            $data['photo'] = $this->photo->store('images', 'public');
        }
        return $data;
    }
}
