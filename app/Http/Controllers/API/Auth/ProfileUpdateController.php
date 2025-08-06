<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileUpdateController extends Controller
{
    use ApiResponse;

    public function changeEmail(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|string|email|unique:users,email,'.$request->user()->id,
        ]);

        if (!$validator) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {

            $user = auth()->user();

            if ($user->provider_id == 'google')
            {
                return $this->error('You do not have the permission to change the email.' , 403);
            }

            $user->email = $request->email;
            $user->save();

            return $this->ok('Email changed successfully', $user);

        }catch (\Exception $exception){
            return $this->error($exception->getMessage(),404);
        }
    }

    public function changePassword(Request $request)
    {
        $validator = $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        if (!$validator) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {

            $user = auth()->user();

            if ($user->provider_id == 'google')
            {
                return $this->error('You do not have the permission to change the password.' , 403);
            }

            if(!Hash::check($request->old_password, $user->password)){
                return $this->error('Incorrect Current Password', 402);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return $this->ok('Password changed successfully');

        }catch (\Exception $exception){
            return $this->error($exception->getMessage(),404);
        }
    }

    public function changeName(Request $request)
    {
        $validator = $request->validate([
            'name' => 'nullable|string|max:255',
        ]);

        if (!$validator) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {

            $user = auth()->user();

            $user->name = $request->name ?? $user->name;
            $user->save();

            return $this->ok('Name changed successfully', $user);

        }catch (\Exception $exception){
            return $this->error($exception->getMessage(),404);
        }
    }

}
