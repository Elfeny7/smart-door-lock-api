<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function totalUsers()
    {
        $totalUsers = DB::table('users')->count();
        return response()->json(['totalUsers' => $totalUsers]);
    }

    public function userAccessedDoors()
    {
        $userAccessedDoors = DB::table('logs')
            ->whereDate('created_at', Carbon::today())
            ->count();
        return response()->json(['userAccessedDoors' => $userAccessedDoors]);
    }

    public function totalDoors()
    {
        $totalDoors = DB::table('doors')->count();
        return response()->json(['totalDoors' => $totalDoors]);
    }

    public function newUsersToday()
    {
        $newUserToday = DB::table('users')
            ->whereDate('created_at', Carbon::today())
            ->count();
        return response()->json(['newUserToday' => $newUserToday]);
    }
}
