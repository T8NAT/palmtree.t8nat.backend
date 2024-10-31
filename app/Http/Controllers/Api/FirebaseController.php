<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FirebaseController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function firebaseUdateDeviceToken(Request $request)
    {
        //
        $request->validate([
            'device_token' => 'required'
        ]);

        $request->user()->update([
            'device_token' => $request->input('device_token')
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'device updated successfully'
        ]);
    }
}
