<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MobileResource;
use App\Models\Mobile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MobileController extends Controller
{
    public function mobileGet()
    {
        $mobile = Mobile::latest()->first();

        if ($mobile) {
            return new MobileResource(true, 'Data Mobile Terbaru', $mobile);
        } else {
            return new MobileResource(false, 'Data mobile tidak ditemukan', null);
        }
    }

    public function mobilePost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|string',
            'door_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mobile = Mobile::create([
            'pin'     => $request->pin,
            'door_id' => $request->door_id,
        ]);

        return new MobileResource(true, 'Data mobile Berhasil Ditambahkan!', $mobile);
    }
}
