<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Door;


class UserDoorController extends Controller
{

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'door_id' => 'required|exists:doors,id',
        ]);
        $user = User::findOrFail($validatedData['user_id']);
        $user->doors()->attach($validatedData['door_id']);
        return response()->json(['message' => 'User Door relation created successfully'], 201);
    }

    public function usersByDoor($doorId)
    {
        $door = Door::with('users')->findOrFail($doorId);
        $users = $door->users;
        return response()->json($users, 200);
    }
}
