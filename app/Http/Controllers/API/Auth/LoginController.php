<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return $this->error('The provided credentials do not match our records.',401,[
                'email' => 'The provided credentials do not match our records.'
            ]);
        }

        $user = Auth::user();

        if (is_null($user->email_verified_at)) {
            $otp = $this->send_otp($user);

            if (!$otp) {
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to send OTP.'
                ], 500);
            }

            return response()->json([
                'status' => false,
                'message' => 'Email not verified.',
                'data' => [
                    'otp' => $otp->token,
                    'email' => $user->email
                ]
            ], 201);
        }

        $user = Auth::user();
        if ($user->role == 'Admin'){
            return $this->error('You are not authorized to access this page.', 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login Successful',
            'token_type' => 'Bearer',
            'token' => $user->createToken('AuthToken')->plainTextToken,
            'data' => $user
        ]);
    }

    public function loginGoogle(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'name' => 'nullable|string', // optional, in case you want to create a user
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->error('The provided credentials do not match our records.',404);
        }

        // If user exists but email not verified, verify it
        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();
        }

        // Block admin role if needed
        if ($user->role === 'Admin') {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to access this page.',
            ], 401);
        }

        // Login the user manually
        Auth::login($user);

        return response()->json([
            'status' => true,
            'message' => 'Login Successful',
            'token_type' => 'Bearer',
            'token' => $user->createToken('AuthToken')->plainTextToken,
            'data' => $user
        ]);
    }

    public function send_otp(User $user,$mailType = 'verify')
    {
        $otp  = (new Otp)->generate($user->email, 'numeric', 6, 60);
        $message = $mailType === 'verify' ? 'Email verificatie' : 'Wachtwoord opnieuw aanmaken';
        \Mail::to($user->email)->send(new \App\Mail\OTP($otp->token,$user,$message,$mailType));
        return $otp;
    }

    public function userDetails()
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error('Unauthorized access.', 401);
        }

        return $this->success('User details retrieved successfully.', $user);
    }

    public function logout(Request $request)
    {
        try {
            // Revoke the current user’s token
            $request->user()->currentAccessToken()->delete();
            // Return a response indicating the user was logged out
            return $this->ok('Logged out successfully.');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage(),500);
        }
    }

}
