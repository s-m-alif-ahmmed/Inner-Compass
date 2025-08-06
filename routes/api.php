<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\SystemSetting\SystemSettingController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\ProfileUpdateController;
use App\Http\Controllers\API\DynamicPage\DynamicPageController;
use App\Http\Controllers\API\Audio\AudioController;
use App\Http\Controllers\API\Audio\FavouriteController;
use App\Http\Controllers\API\WelcomeAudio\WelcomeAudioController;
use App\Http\Controllers\API\Firebase\FirebaseTokenController;
use App\Http\Controllers\API\Auth\SocialAuthController;


// Common routes
Route::get('/system-setting', [SystemSettingController::class, 'systemSetting']);
Route::get('/welcome-audio', [WelcomeAudioController::class, 'welcomeAudio']);

// Dynamic Pages routes
Route::get('/dynamic-page/list', [DynamicPageController::class, 'index']);
Route::get('/dynamic-page/show/{slug}', [DynamicPageController::class, 'show']);

Route::controller(FirebaseTokenController::class)->group(function () {
    Route::post('/firebase-token/add', 'updateFirebaseToken');
    Route::post('/firebase-token/delete', 'deleteFirebaseToken');
});

// Google Login
Route::post('/login-google', [SocialAuthController::class, 'socialLogin']);
Route::post('/auth/apple/callback', [SocialAuthController::class, 'redirectCallbackApple']);

Route::middleware(['guest'])->group(function () {

    //  Authentication routes
    Route::post('login',[LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('resend_otp', [RegisterController::class, 'resend_otp']);
    Route::post('verify_otp', [RegisterController::class, 'verify_otp']);
    Route::post('forgot-password', [RegisterController::class, 'forgot_password']);
    Route::post('forgot-verify-otp', [RegisterController::class, 'forgot_verify_otp']);
    Route::post('reset-password', [RegisterController::class, 'reset_password']);

});


Route::group(['middleware' => 'auth:sanctum'], function ($router) {

    // common routes
    Route::get('/user-detail', [LoginController::class, 'userDetails']);
    Route::post('/logout', [LoginController::class, 'logout']);

    // Profile Update routes
    Route::post('/change-password', [ProfileUpdateController::class, 'changePassword']);
    Route::post('/change-email', [ProfileUpdateController::class, 'changeEmail']);
    Route::post('/change-name', [ProfileUpdateController::class, 'changeName']);

    // Audio routes
    Route::get('/audio/list', [AudioController::class, 'index']);
    Route::get('/audio/show/{id}', [AudioController::class, 'show']);

    // Audio favourites routes
    Route::get('/audio/favourite/list', [FavouriteController::class, 'index']);
    Route::post('/audio/favourite/store/{id}', [FavouriteController::class, 'store']);
    Route::post('/audio/favourite/remove/{id}', [FavouriteController::class, 'destroy']);

});


Route::get('/run-composer-update', function () {
    abort_unless(request()->query('key') === env('SECURE_KEY'), 403);

    $path = base_path(); // This gets the Laravel root directory (where composer.json lives)

    try {
        $output = shell_exec("cd {$path} && composer update 2>&1");
        return response("<pre>$output</pre>");
    } catch (\Exception $e) {
        return response("Error: " . $e->getMessage(), 500);
    }
});
