<?php

namespace App\Http\Requests\Item;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class IndexItemRequest extends FormRequest
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
            'orderBy' => 'nullable|string|in:code,name,quantity,category_id,type_id,room_id,created_at,updated_at',

            'code' => 'nullable|string',
            'order' => 'nullable|string|in:asc,desc',
            'limit' => 'nullable|integer|min:1',
            'search' => 'nullable|string',
            'room_id' => 'nullable|integer',
            'checkin_id' => 'nullable|integer',
            'withTrashed' => 'nullable|boolean',
            'only_mines' => 'nullable|boolean',
        ];
    }
}
