<?php

namespace App\Http\Controllers\API\Audio;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AudioController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $per_page = $request->per_page;

        $user = Auth::user();

        $data = Audio::where('status', 'Active')
            ->with(['favourites' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->paginate($per_page);

        $data->getCollection()->transform(function ($audio) use ($user) {
            $audio->favourite_status = $audio->favourites->isNotEmpty();
            unset($audio->favourites);
            return $audio;
        });

        if ($data) {
            return $this->ok('Data Retrieve Successfully!', $data, 200);
        }

        return $this->error("Data not found", 500);
    }

    public function show($id)
    {
        $user = Auth::user();

        $audio = Audio::where('status', 'Active')->find($id);

        if (!$audio) {
            return $this->error('Audio not found', 404);
        }

        $isFavourite = $audio->favourites()
            ->where('user_id', $user->id)
            ->exists();

        $audio->favourite_status = $isFavourite;

        return $this->ok('Data Retrieve Successfully!',$audio, 200);
    }
}
