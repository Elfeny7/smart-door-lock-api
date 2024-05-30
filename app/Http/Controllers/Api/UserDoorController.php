<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDoor;
use App\Http\Resources\UserDoorResource;
use Illuminate\Support\Facades\Validator;

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
}
