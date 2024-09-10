<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
        $item = request()->route('item');
        return [
            'name' => 'required',
            'code' => 'required|unique:items,code,' . $item->id,
            'quantity' => 'required',
            'unit' => 'required',
            'category_id' => 'required|exists:categories,id',
            'type_id' => 'required|exists:types,id',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = $this->validator->validated();
        if ($this->has('photo') && $this->photo && $this->photo != 'null') {
            $data['photo'] = $this->photo->store('images', 'public');
        }
        return $data;
    }
}
