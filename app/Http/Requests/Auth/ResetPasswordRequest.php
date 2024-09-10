<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class ResetPasswordRequest extends FormRequest
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
            'old_password' => 'required',
            'new_password' => 'required',
            'new_password_confirmation' => 'required',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = $this->validator->validated();

        if ($data['new_password'] !== $data['new_password_confirmation']) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'The new password confirmation does not match',
                    'errors' => [
                        'new_password_confirmation' => [
                            'The new password confirmation does not match',
                        ]
                    ]
                ], 422)
            );
        }

        if (!Hash::check($data['old_password'], request()->user()->password)) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'The current password field does not match',
                    'errors' => [
                        'old_password' => [
                            'The current password field does not match',
                        ]
                    ]
                ], 422)
            );
        }
        return $data;
    }
}
