<?php

namespace App\Http\Controllers\API\Firebase;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\FirebaseToken;
use Illuminate\Support\Facades\Validator;

class FirebaseTokenController extends Controller
{
    use ApiResponse;

    public function updateFirebaseToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|unique:firebase_tokens',
            'device_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $user = auth()->user();

        $token = $request->token;
        $device_id = $request->device_id;

        $firebaseToken = FirebaseToken::where('device_id', $device_id)->where('user_id', $user->id)->first();

        if ($firebaseToken) {
            $firebaseToken->update([
                'user_id' => $user->id,
                'token' => $token
            ]);
            return $this->success('Token updated successfully', $firebaseToken, 200);
        } else {
            $firebaseToken = FirebaseToken::create([
                'user_id' => $user->id,
                'token' => $token,
                'device_id' => $device_id
            ]);
            return $this->success('Token created successfully', $firebaseToken, 200);
        }
    }

    public function deleteFirebaseToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $device_id = $request->device_id;

        $firebaseToken = FirebaseToken::where('device_id', $device_id)->first();

        if ($firebaseToken) {
            $firebaseToken->delete();
            return $this->success('Token deleted successfully', [], 200);
        } else {
            return $this->error('Token not found', 404);
        }
    }
}
