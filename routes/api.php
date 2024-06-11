<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DoorController;
use App\Http\Controllers\Api\UserDoorController;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;


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

Route::middleware('auth:api')->get('/admin', function (Request $request) {
    return $request->user();
});
Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

Route::apiResource('/users', UserController::class);
Route::get('/user/{id}','App\Http\Controllers\Api\UserController@getUserById');
Route::get('/user/pin/{pin}','App\Http\Controllers\Api\UserController@getUserByPin');
Route::get('/user/{email}/{pin}','App\Http\Controllers\Api\UserController@getUserByEmailAndPin');
Route::get('/user-door/{doorId}/users', [UserDoorController::class, 'usersByDoor']);

Route::apiResource('/doors', DoorController::class);
Route::get('/door/{id}','App\Http\Controllers\Api\DoorController@getDoorById');

Route::post('/user-door/attach', [UserDoorController::class, 'attach']);
Route::post('/user-door/detach', [UserDoorController::class, 'detach']);
Route::post('/check-access', [UserDoorController::class, 'checkAccess']);

Route::get('/total-users', [DashboardController::class, 'totalUsers']);
Route::get('/user-accessed-doors', [DashboardController::class, 'userAccessedDoors']);
Route::get('/total-doors', [DashboardController::class, 'totalDoors']);
Route::get('/new-users-today', [DashboardController::class, 'newUsersToday']);

Route::apiResource('/logs', LogController::class);
