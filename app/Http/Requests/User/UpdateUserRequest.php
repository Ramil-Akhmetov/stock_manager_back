<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdateUserRequest extends FormRequest
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
            'phone' => 'sometimes|string',
            'roles' => 'required',
            'name' => 'sometimes|string|max:50',
            'email' => 'sometimes|string|email|unique:users,email,' . $this->user()->id,
            'password' => 'sometimes|string|confirmed|min:8',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = $this->validator->validated();
        if ($this->input('password')) {
            $data['password'] = Hash::make($this->input('password'));
        }
        return $data;
    }
}
