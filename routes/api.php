<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DoorController;
use App\Http\Controllers\Api\UserDoorController;
use App\Http\Controllers\Api\LogController;
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

Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('login');
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');
Route::middleware('auth:api')->get('/admin', function (Request $request) {
    return $request->user();
});
Route::apiResource('/users', UserController::class);
Route::apiResource('/doors', DoorController::class);
Route::post('/user-door/attach', [UserDoorController::class, 'attach']);
Route::post('/user-door/detach', [UserDoorController::class, 'detach']);
Route::get('/user-door/{doorId}/users', [UserDoorController::class, 'usersByDoor']);
Route::apiResource('/logs', LogController::class);

// New Routes for Dashboard Data
Route::get('/total-users', function () {
    $totalUsers = DB::table('users')->count();
    return response()->json(['totalUsers' => $totalUsers]);
});

Route::get('/user-accessed-doors', function () {
    $userAccessedDoors = DB::table('logs')->whereDate('access_time', Carbon::today())->count();
    return response()->json(['userAccessedDoors' => $userAccessedDoors]);
});

Route::get('/total-doors', function () {
    $totalDoors = DB::table('doors')->count();
    return response()->json(['totalDoors' => $totalDoors]);
});

Route::get('/new-users-today', function () {
    $newUserToday = DB::table('users')->whereDate('created_at', Carbon::today())->count();
    return response()->json(['newUserToday' => $newUserToday]);
});
