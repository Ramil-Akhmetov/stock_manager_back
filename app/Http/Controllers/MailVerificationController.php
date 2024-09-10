<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MailVerificationController extends Controller
{
    //TODO https://stackoverflow.com/questions/65285530/laravel-8-rest-api-email-verification

    public function must_verify_error(Request $request) {
        return \response()->json(['message' => 'Must verify email'], 403);
    }

    //TODO try verifying user maybe change frontend routes
    public function verify(Request $request)
    {
        $user_id = $request->route('id');
        $user = User::find($user_id);

        if ($user->hasVerifiedEmail()) {
            return redirect(env('FRONTEND_URL') . '/login');
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        return redirect(env('FRONTEND_URL') . '/login');
    }

    public function resend(Request $request)
    {
        //TODO check how resend works, maybe change it
        $user = $request->user();
        dispatch(function () use ($user) {
            $user->sendEmailVerificationNotification();
        });
    }
}
