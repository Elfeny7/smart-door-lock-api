<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {

        $users = User::latest()->paginate(5);
        return new UserResource(true, 'List Data User', $users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'role'     => 'required',
            'phone'   => 'required',
            'email'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'role'     => $request->role,
            'phone'   => $request->phone,
            'email'   => $request->email,
        ]);

        return new UserResource(true, 'Data Users Berhasil Ditambahkan!', $user);
    }

    public function show(User $user)
    {
        return new UserResource(true, 'Data User Ditemukan!', $user);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'role'     => 'required',
            'phone'   => 'required',
            'email'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update([
            'name'     => $request->name,
            'role'   => $request->role,
            'phone'   => $request->phone,
            'email'   => $request->email,
        ]);

        return new UserResource(true, 'Data User Berhasil Diubah!', $user);
    }
}
