<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    use ApiResponse;

    public function socialLogin(Request $request)
    {
        $request->validate([
            'provider_id' => 'required',
            'token' => 'required',
        ]);

        try {
            if ($request->provider_id === 'google') {
                $socialUser = Socialite::driver('google')->stateless()->userFromToken($request->token);
            } elseif ($request->provider_id === 'apple') {
                $socialUser = Socialite::driver('apple')->stateless()->userFromToken($request->token);
            } else {
                return $this->error('Unsupported provider', 422);
            }

            if ($socialUser) {
                $user = User::where('email', $socialUser->email)->first();

                if (!$user) {
                    $password = Str::random(16);
                    $user = User::create([
                        'provider_id' => $request->provider_id,
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->email,
                        'password' => Hash::make($password),
                        'avatar' => $socialUser->getAvatar(),
                        'email_verified_at' => now(),
                        'terms' => true,
                        'role' => 'User',
                    ]);
                }

                Auth::login($user);

                $token = $user->createToken('AuthToken')->plainTextToken;

                $info = User::where('email' , $user->email)->first();

                return response()->json([
                    'status' => true,
                    'message' => 'Login Successful',
                    'token_type' => 'Bearer',
                    'token' => $token,
                    'data' => $info ,
                ]);
            } else {

                return $this->error('Invalid or Expired Token', 401);
            }
        } catch (Exception $e) {
            \Log::error('Social login failed: ' . $e->getMessage());
            return $this->error('Something went wrong', 500);
        }
    }

    public function redirectCallbackApple()
    {
        return $this->ok('You are now logged in');
    }
}
