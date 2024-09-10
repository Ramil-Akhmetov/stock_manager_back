<?php

namespace App\Http\Requests\Auth;

use App\Models\InviteCode;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterRequest extends FormRequest
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
            'invite_code' => 'required|string',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|string',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = $this->validator->validated();
        if ($data['password'] !== $data['password_confirmation']) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'The password confirmation does not match',
                    'errors' => [
                        'password_confirmation' => [
                            'The password confirmation does not match',
                        ]
                    ]
                ], 422)
            );
        }
        //TODO check how long should be remember_token
        //TODO should delete remember_token from everywhere

        $data['remember_token'] = Str::random(60);

        if (!InviteCode::where('code', $data['invite_code'])->first()) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'The invite code is invalid',
                    'errors' => [
                        'invite_code' => [
                            'The invite code is invalid',
                        ]
                    ]
                ], 422)
            );
        }

        if ($this->input('password')) {
            $data['password'] = Hash::make($this->input('password'));
        }
        return $data;
    }
}
