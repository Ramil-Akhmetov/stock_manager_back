<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class StoreUserRequest extends FormRequest
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
            'roles' => 'required',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'patronymic' => 'required|string|max:255',
            'email' => 'sometimes|string|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'photo' => 'nullable|image',
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
