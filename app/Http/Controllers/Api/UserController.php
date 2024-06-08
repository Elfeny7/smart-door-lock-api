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

        $users = User::latest()->get();
        return new UserResource(true, 'List Data User', $users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'role'     => 'required',
            'pin'     => 'required',
            'phone'   => 'required',
            'email'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'role'     => $request->role,
            'pin'     => $request->pin,
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
            'pin'     => 'required',
            'phone'   => 'required',
            'email'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update([
            'name'     => $request->name,
            'role'   => $request->role,
            'pin'   => $request->pin,
            'phone'   => $request->phone,
            'email'   => $request->email,
        ]);

        return new UserResource(true, 'Data User Berhasil Diubah!', $user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return new UserResource(true, 'Data User Berhasil Dihapus!', null);
    }

    public function getUserById($id)
    {
        $user = User::findOrFail($id);
        return new UserResource(true, 'Data User Ditemukan!', $user);
    }

    public function getUserByPin($pin)
    {
        $user = User::where('pin', $pin)->first();

        if (!$user) {
            return response()->json(['message' => 'Pengguna dengan PIN tersebut tidak ditemukan'], 404);
        }

        return new UserResource(true, 'Data Pengguna Ditemukan berdasarkan PIN!', $user);
    }
}
