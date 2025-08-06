<?php

namespace Database\Seeders;

use App\Models\WelcomeAudio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WelcomeAudioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $audio = [
            [
                'title' => 'Welkom',
                'audio' => null,
                'duration' => 0,
            ],
        ];

        foreach ($audio as $data) {
            WelcomeAudio::create([
                'title' => $data['title'],
                'audio' => $data['audio'],
                'duration' => $data['duration'],
            ]);
        }
    }
}
