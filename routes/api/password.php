<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Password API Routes
|--------------------------------------------------------------------------
*/


Route::post('/forgot_password', [\App\Http\Controllers\PasswordController::class, 'forgot_password']);
Route::post('/reset_forgot_password', [\App\Http\Controllers\PasswordController::class, 'reset_forgot_password']);

Route::post('/reset_password', [\App\Http\Controllers\PasswordController::class, 'reset_password'])
    ->middleware('auth:api');
