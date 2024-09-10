<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


include_once __DIR__ . '/api/auth.php';
include_once __DIR__ . '/api/mail.php';
include_once __DIR__ . '/api/password.php';


Route::apiResource('users', \App\Http\Controllers\UserController::class);
Route::apiResource('categories', \App\Http\Controllers\CategoryController::class);
Route::apiResource('items', \App\Http\Controllers\ItemController::class);
Route::apiResource('types', \App\Http\Controllers\TypeController::class);
Route::apiResource('room_types', \App\Http\Controllers\RoomTypeController::class);
Route::apiResource('rooms', \App\Http\Controllers\RoomController::class);
Route::apiResource('confirmations', \App\Http\Controllers\ConfirmationController::class);
Route::apiResource('suppliers', \App\Http\Controllers\SupplierController::class);
Route::apiResource('checkins', \App\Http\Controllers\CheckinController::class);
Route::apiResource('checkouts', \App\Http\Controllers\CheckoutController::class);
Route::apiResource('transfers', \App\Http\Controllers\TransferController::class);
Route::apiResource('roles', \App\Http\Controllers\RoleController::class);
Route::apiResource('responsibilities', \App\Http\Controllers\ResponsibilityController::class);

Route::apiResource('permissions', \App\Http\Controllers\PermissionController::class)->only(['index', 'show']);
Route::apiResource('activities', \App\Http\Controllers\ActivityController::class)->only(['index', 'show']);

Route::apiResource('invite_codes', \App\Http\Controllers\InviteCodeController::class)->only(['index', 'show', 'destroy']);

Route::apiResource('transfer_statuses', \App\Http\Controllers\TransferStatusController::class)->only(['index']);

Route::post('transfers/{transfer}/change_status', [\App\Http\Controllers\TransferController::class, 'changeStatus']);
Route::post('change_email', [\App\Http\Controllers\UserController::class, 'changeEmail']);

Route::apiResource('racks', \App\Http\Controllers\RackController::class)->only('index', 'show');
