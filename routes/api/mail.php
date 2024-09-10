<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mail API Routes
|--------------------------------------------------------------------------
*/

//TODO create routes to change email for user
//TODO maybe should change some middlewares

Route::get('/email/verify', [\App\Http\Controllers\MailVerificationController::class, 'must_verify_error'])
    ->middleware('auth:api')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [\App\Http\Controllers\MailVerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verify/resend', [\App\Http\Controllers\MailVerificationController::class, 'resend'])
    ->middleware(['auth:api', 'throttle:6,1'])
    ->name('verification.send');

//mail
Route::get('mail-test', [\App\Http\Controllers\MailController::class, 'test'])
    ->middleware('auth:api');
