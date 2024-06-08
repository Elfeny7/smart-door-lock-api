<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Door;


class UserDoorController extends Controller
{

    public function attach(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'door_id' => 'required|exists:doors,id',
        ]);
        $user = User::findOrFail($validatedData['user_id']);
        $user->doors()->attach($validatedData['door_id']);

        return response()->json(['message' => 'User Door relation created successfully'], 201);
    }

    public function detach(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'door_id' => 'required|exists:doors,id',
        ]);

        $user = User::findOrFail($validatedData['user_id']);
        $user->doors()->detach($validatedData['door_id']);

        return response()->json(['message' => 'User Door relation removed successfully'], 200);
    }

    public function usersByDoor($doorId)
    {
        $door = Door::with('users')->findOrFail($doorId);
        $users = $door->users;
        return response()->json($users, 200);
    }

    public function checkAccess(Request $request)
    {
        $validatedData = $request->validate([
            'pin' => 'required|string',
            'door_id' => 'required|exists:doors,id',
        ]);

        $user = User::where('pin', $validatedData['pin'])->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $hasAccess = $user->doors()->where('door_id', $validatedData['door_id'])->exists();

        if ($hasAccess) {
            return response()->json(['message' => 'Access granted'], 200);
        } else {
            return response()->json(['message' => 'Access denied'], 403);
        }
    }
}
