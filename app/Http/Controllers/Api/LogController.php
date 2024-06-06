<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogResource;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LogController extends Controller
{

    public function index()
    {
        $logs = Log::latest()->get();
        return new LogResource(true, 'List Data Logs', $logs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'role'       => 'required',
            'class_name' => 'required',
            'image'      => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/image', $image->hashName());

        $log = Log::create([
            'name'       => $request->name,
            'role'       => $request->role,
            'class_name' => $request->class_name,
            'image'      => $image->hashName(),
        ]);

        return new LogResource(true, 'Data Log Berhasil Ditambahkan!', $log);
    }
}
