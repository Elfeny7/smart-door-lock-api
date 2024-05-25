<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Door;
use App\Http\Resources\DoorResource;
use Illuminate\Support\Facades\Validator;

class DoorController extends Controller
{

    public function index()
    {
        $doors = Door::latest()->paginate(5);
        return new DoorResource(true, 'List Data Doors', $doors);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'location'     => 'required',
            'class_name'   => 'required',
            'description'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $door = Door::create([
            'name'     => $request->name,
            'location'     => $request->location,
            'class_name'   => $request->class_name,
            'description'   => $request->description,
        ]);

        return new DoorResource(true, 'Data Door Berhasil Ditambahkan!', $door);
    }

    public function show(Door $door)
    {
        return new DoorResource(true, 'Data Door Ditemukan!', $door);
    }
}
