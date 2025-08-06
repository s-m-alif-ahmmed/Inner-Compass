<?php

namespace App\Http\Controllers\API\Audio;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\Favourite;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouriteController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $per_page = $request->per_page;

        $user = Auth::user();

        $data = Favourite::with('audio')
            ->where('user_id', $user->id)
            ->paginate($per_page);

        if ($data) {
            return $this->ok('Data Retrieve Successfully!', $data, 200);
        }

        return $this->error("Data not found", 500);
    }

    public function store($id)
    {
        $userId = Auth::id();

        $exists = Favourite::where('user_id', $userId)
            ->where('audio_id', $id)
            ->exists();

        if ($exists) {
            return $this->error("Audio already exists", 500);
        }

        $data = Favourite::create([
            'user_id' => $userId,
            'audio_id' => $id,
        ]);

        return $this->ok('Favourite added successfully!', $data->load('audio'), 200);

    }

    public function destroy($id)
    {
        $userId = Auth::id();

        $favourite = Favourite::where('user_id', $userId)
            ->where('audio_id', $id)
            ->first();

        if (!$favourite) {
            return $this->error("Audio not found", 404);
        }

        $favourite->delete();

        return $this->success('Audio removed from favourites!', $favourite->load('audio'), 200);
    }

}
