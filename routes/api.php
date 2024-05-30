<?php

use App\Http\Controllers\Api\UserDoorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/users', App\Http\Controllers\Api\UserController::class);
Route::apiResource('/doors', App\Http\Controllers\Api\DoorController::class);
Route::apiResource('/user-door', App\Http\Controllers\Api\UserDoorController::class);
Route::get('/user-door/{doorId}/users',[UserDoorController::class, 'usersByDoor']);

