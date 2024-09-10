<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function test(Request $request) {
        $user = $request->user();
        dispatch(function () use ($user) {
            Mail::to($user)->send(new TestMail());
        });
        return \response()->json(['message' => 'Email sent!']);
    }
}
