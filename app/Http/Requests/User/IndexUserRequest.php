<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class IndexUserRequest extends FormRequest
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
            'orderBy' => 'nullable|string|in:surname,email,phone',

            'order' => 'nullable|string|in:asc,desc',
            'limit' => 'nullable|integer|min:1',
            'search' => 'nullable|string',
            'role_id' => 'nullable|integer|exists:roles,id'
        ];
    }
}
