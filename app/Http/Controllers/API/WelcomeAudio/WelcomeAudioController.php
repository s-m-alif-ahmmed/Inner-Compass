<?php

namespace App\Http\Controllers\API\WelcomeAudio;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\WelcomeAudio;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class WelcomeAudioController extends Controller
{
    use ApiResponse;

    public function welcomeAudio(Request $request)
    {
//        $data = WelcomeAudio::first();
        $data = Audio::orderBy('id', 'asc')->first();

        if ($data) {
            return $this->ok('Data Retrieve Successfully!',$data, 200);
        }

        return $this->error("Welcome Audio not found", 500);
    }
}
