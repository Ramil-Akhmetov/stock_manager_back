<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function reset_password(ResetPasswordRequest $request)
    {
        $validated = $request->validated();
        $request->user()->update([
            'password' => Hash::make($validated['new_password']),
            'remember_token' => Str::random(60),
        ]);
        //TODO send notification
        return response()->json(['message' => 'Password updated']);
    }

    public function forgot_password(ForgotPasswordRequest $request)
    {
        $validated = $request->validated();

        dispatch(function () use ($validated) {
            Password::sendResetLink($validated);
        });
    }

    public function reset_forgot_password(ResetForgotPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => $status])
            : response()->json(['message' => $status], 500);
    }
}
